<?php
/**
 * Created by PhpStorm.
 * User: Alejandro
 * Date: 5/8/2019
 * Time: 6:19 p.m.
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    public $table = "galerias";
    public $primaryKey = "idgalerias";
    const CREATED_AT = 'fechacreacion';
    const UPDATED_AT = 'fechamodificacion';


    public function getFechaAttribute(){
        return $this->fechacreacion;
    }

    public function getIdAttribute(){
        return $this->idgalerias;
    }

    public function images(){
        return $this->hasMany('App\Image', 'galeria');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'autor');
    }

    public function imagesJSON(){
        $images = json_decode($this->images);
        return $images;
    }

    public function updateImagesJSON(){
        $images = json_encode($this->images);
        $this->imagenes = $images;
        $this->save();
    }

    public function getLastPosicion(){
        return $this->images()->max('posicion');
    }

    public function getPortada(){
        if($this->portada)
            return $this->portada;
        else
            if($this->images->first())
                return $this->images->first()->location;
            else return '';
    }

    public static function getLatest($limit){
        $galleries = Gallery::where('publicado', 1)->orderBy('fechacreacion', 'desc')->limit($limit)->get();
        return $galleries;
    }

    public function getFirstUrl(){
        return route('gallery', ['gallery'=>$this->idgalerias]);
    }
}