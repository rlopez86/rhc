<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alejandro
 * Date: 25/03/13
 * Time: 4:23
 * To change this template use File | Settings | File Templates.
 */

namespace App;

class HelperImages
{
    public $thumbnail_width;
    public $thumbnail_height;
    public $landscape_width;
    public $landscape_height;
    public $portrait_height;
    public $portrait_width;
    public $preserve_ratio;
    public $orientation;

    public static $ORIENTATION_HEIGHT = 1;
    public static $ORIENTATION_WIDTH = 2;

    public static $BOTTOM = -1;
    public static $TOP = 1;
    public static $RIGHT = -2;
    public static $LEFT = 2;
    public static $WIDTH = 3;
    public static $HEIGHT = -3;
    public static $BEST = 4;

    public function init(){}


    public function createThumbnailPortada($src, $dst, $crop=true, $width=180, $height=140){
        list($w, $h) = getimagesize($src);
        if($crop){
            if(($w - $width*$h/$height)<($h - $height*$w/$width))
                return $this->rigidResizeImage($src,$dst,$width,$height,HelperImages::$HEIGHT);
            return $this->rigidResizeImage($src,$dst,$width,$height,HelperImages::$WIDTH);
        }

    }

    public function rigidResizeImage($src, $dst, $width, $height, $crop=false, $conserve_ratio=true){

        if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
        if($w < $width or $h < $height) return "Picture is too small!";
        $type = strtolower(substr(strrchr($src,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($src); break;
            case 'gif': $img = imagecreatefromgif($src); break;
            case 'jpg': $img = imagecreatefromjpeg($src); break;
            case 'png': $img = imagecreatefrompng($src); break;
            default : return "Unsupported picture type!";
        }

        $new = imagecreatetruecolor($width, $height);

        // preserve transparency
        if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }
        if($crop){
            switch($crop){
                case HelperImages::$LEFT:
                    $x = round($w - $width*$h/$height);
                    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w-$x, $h);
                    break;
                case HelperImages::$TOP:
                    $y = round($h - $height*$w/$width);
                    imagecopyresampled($new, $img, 0, 0, 0, $y, $width, $height, $w, $h-$y);
                    break;
                case HelperImages::$RIGHT:
                    $x = round($w - $width*$h/$height);
                    imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w-$x, $h);
                    break;
                case HelperImages::$WIDTH:
                    $x = round(($w - $width*$h/$height)/2);
                    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w-2*$x, $h);
                    break;
                case HelperImages::$HEIGHT:
                    $y = round(($h - $height*$w/$width)/2);
                    imagecopyresampled($new, $img, 0, 0, 0, $y, $width, $height, $w, $h-2*$y);
                    break;
                default:
                    $y = round($h - $height*$w/$width);
                    imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w, $h-$y);

            }
        }
        elseif($conserve_ratio){
            $ratio = min($width/$w, $height/$h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $new = imagecreatetruecolor($width, $height);
            imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w, $h);
        }
        else{
            imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w, $h);
        }


        switch($type){
            case 'bmp': imagewbmp($new, $dst); break;
            case 'gif': imagegif($new, $dst); break;
            case 'jpg': imagejpeg($new, $dst); break;
            case 'png': imagepng($new, $dst); break;
        }
        return true;
    }


    function createThumbnail($src, $dst, $crop=false, $width=false, $height=false){
        $width ? : $width = $this->thumbnail_width;
        $height ? : $height = $this->thumbnail_height;
        $crop ? : $crop = $this->preserve_ratio;
        if($crop)
            $this->resizeImage($src,$dst,$width,$height,$crop);
        else
            $this->thumbnailByValue($src,$dst);
    }

    /**
     * @param $src
     * @param $dst
     * @param $width
     * @param $height
     * @param $crop
     * @return bool|string
     *
     * Redimensiona basado en los valores por defecto, crea un thumbnail acomodandose a la orientacion de la imagen
     * en caso de especificarse el aspect ratio puede ser ignorado.
     */
    function resizeImage($src, $dst, $width, $height, $crop){

        if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

        $type = strtolower(substr(strrchr($src,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($src); break;
            case 'gif': $img = imagecreatefromgif($src); break;
            case 'jpg': $img = imagecreatefromjpeg($src); break;
            case 'png': $img = imagecreatefrompng($src); break;
            default : return "Unsupported picture type!";
        }

        // resize
        if($crop){
            if($w < $width and $h < $height) return "Picture is too small!";
            $ratio = min($width/$w, $height/$h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }
        else{
            if($w < $width or $h < $height) return "Picture is too small!";
            $ratio = max($width/$w, $height/$h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        }

        $new = imagecreatetruecolor($width, $height);

        // preserve transparency
        if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        switch($type){
            case 'bmp': imagewbmp($new, $dst); break;
            case 'gif': imagegif($new, $dst); break;
            case 'jpg': imagejpeg($new, $dst); break;
            case 'png': imagepng($new, $dst); break;
        }
        return true;
    }

    public function croppedThumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
        //getting the image dimensions
        list($width_orig, $height_orig) = getimagesize($imgSrc);
        $myImage = imagecreatefromjpeg($imgSrc);
        $ratio_orig = $width_orig/$height_orig;

        if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
            $new_height = $thumbnail_width/$ratio_orig;
            $new_width = $thumbnail_width;
        } else {
            $new_width = $thumbnail_height*$ratio_orig;
            $new_height = $thumbnail_height;
        }

        $x_mid = $new_width/2;  //horizontal middle
        $y_mid = $new_height/2; //vertical middle

        $process = imagecreatetruecolor(round($new_width), round($new_height));

        imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
        $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

        imagedestroy($process);
        imagedestroy($myImage);
        return $thumb;

    }

    public function thumbnailByValue($src, $dst, $orientation=1, $width=false, $height=false){
        $width ? : $width = $this->thumbnail_width;
        $height ? : $height = $this->thumbnail_height;

        if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

        $ratio = $height/$h;
        if($orientation == HelperImages::$ORIENTATION_WIDTH){
            $ratio = $width/$w;
        }
        $width = $w * $ratio;
        $height = $h * $ratio;
        $x = 0;

        $type = strtolower(substr(strrchr($src,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($src); break;
            case 'gif': $img = imagecreatefromgif($src); break;
            case 'jpg': $img = imagecreatefromjpeg($src); break;
            case 'png': $img = imagecreatefrompng($src); break;
            default : return "Unsupported picture type!";
        }

        $new = imagecreatetruecolor($width, $height);

        // preserve transparency
        if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

        switch($type){
            case 'bmp': imagewbmp($new, $dst); break;
            case 'gif': imagegif($new, $dst); break;
            case 'jpg': imagejpeg($new, $dst); break;
            case 'png': imagepng($new, $dst); break;
        }
        return true;

    }

    public function reSampleImage($src, $dst, $x, $y, $h, $w,   $landscape = 1, $nh=false, $nw=false){

        if(!$nh)
            if($landscape == 1)
                $nh = $this->landscape_height;
            else
                $nh = $this->portrait_height;
        if(!$nw)
            if($landscape == 1)
                $nw = $this->landscape_width;
            else
                $nw = $this->portrait_width;
        $type = strtolower(substr(strrchr($src,"."),1));
        if($type == 'jpeg') $type = 'jpg';
        switch($type){
            case 'bmp': $img = imagecreatefromwbmp($src); break;
            case 'gif': $img = imagecreatefromgif($src); break;
            case 'jpg': $img = imagecreatefromjpeg($src); break;
            case 'png': $img = imagecreatefrompng($src); break;
            default : return "Unsupported picture type!";
        }

        $new = imagecreatetruecolor($nw, $nh);

        // preserve transparency
        if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
        }

        imagecopyresampled($new, $img, 0, 0, $x, $y, $nw, $nh, $w, $h);

        switch($type){
            case 'bmp': imagewbmp($new, $dst); break;
            case 'gif': imagegif($new, $dst); break;
            case 'jpg': imagejpeg($new, $dst); break;
            case 'png': imagepng($new, $dst); break;
        }
        return true;
    }

}
