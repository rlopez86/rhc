<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\HelperImages;
use App\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request){
        $name = random_int(1, 99999999).'-'.str_slug($request->file('file')->getClientOriginalName());
        $path = $request->file('file')->storeAs(
            'articles', $name
        );
        $image = new Image;
        $image->imagen = $name;
        $image->location = $path;
        $image->galeria = 0;
        $image->fecha = Carbon::now();
        $image->save();
        if($request->input('prev')!='none'){
            if(Storage::disk('local')->delete($request->input('prev'))){
                Image::where('location','=',$request->input('prev'))->delete();
            }
        }
        return $image;
    }

    public function uploadToGallery(Request $request, $gallery){
        $gallery = Gallery::findOrFail($gallery);
        $parts = explode('.',$request->file('file')->getClientOriginalName());
        $ext = $parts[count($parts) - 1];
        $name = random_int(1, 99999999).'-'.str_slug($request->file('file')->getClientOriginalName()).'.'.$ext;
        $path = $request->file('file')->storeAs(
            'galleries/'.$gallery->location, $name
        );
        (new HelperImages())->createThumbnailPortada(public_path($path), public_path('galleries/'.$gallery->location.'/thumbnails/'.$name));
        $image = new Image;
        $image->imagen = $name;
        $image->location = $name; //TODO cleanup path
        $image->galeria = $gallery->idgalerias;
        $image->fecha = Carbon::now();
        $image->posicion = $gallery->getLastPosicion() + 1;
        $image->save();
        $gallery->updateImagesJSON();
        return $image;
    }

    public function search(Request $request){
        $text = $request->input('query');
        $offset = $request->input('offset', 0);
        $origin = $request->input('origin');
        $date = $request->input('date');
        $command = Image::query();
        if($text)
            $command->where(function($query) use($text){
                $query->where('imagen', 'like', '%'.$text.'%')->orWhere('descripcion', 'like', '%'.$text.'%');
            });

        if($origin){
            $command->where('origen', 'like', '%'.$origin.'%');
        }
        if($date){
            $parts = explode('/', $date);
            if(count($parts) == 1){
                $command->whereYear('fecha', $parts[0]);
            }
            else if(count($parts) == 2){
                $command->whereYear('fecha', $parts[1]);
                $command->whereMonth('fecha', $parts[0]);
            }
            else if(count($parts) == 3){
                $command->whereYear('fecha', $parts[2]);
                $command->whereMonth('fecha', $parts[1]);
                $command->whereDay('fecha', $parts[0]);
            }

        }
        $images =  $command->offset($offset)->limit(12)->get();
        return $images;
    }

    public function upload_cropped(Request $request){
        $original = Image::findOrFail($request->input('id'));
        $image64 = $request->input('data');

        $parts = explode(',', $image64);
        $data = base64_decode($parts[1]);
        $filename = str_random(4).'-'.$original->imagen;
        $file = base_path('public/articles/cropped/'.$filename);
        file_put_contents($file, $data);
        $image = new Image;
        $image->galeria = 0;
        $image->parent = $original->idimagenes;
        $image->location = 'articles/cropped/'.$filename;
        $image->fecha = Carbon::now();
        $image->imagen = $request->input('name') ? $request->input('name') : $filename;
        $image->descripcion = $request->input('description');
        $image->origen = $request->input('origin');
        $image->save();
        //update original
        $original->imagen = $request->input('name') ? $request->input('name') : $filename;
        $original->descripcion = $request->input('description');
        $original->origen = $request->input('origin');
        $original->save();
        return $image;
    }

    public function cropped_history(Request $request){
        $parent = Image::findOrFail($request->input('id'));
        return $parent->croppeds;
    }

    public function setDescription(Request $request){
        $image = Image::findOrfail($request->input('id'));
        $image->descripcion = $request->input('description');
        $image->save();
        return $image;
    }

    public function delete(Request $request){
        $image = Image::findOrfail($request->input('id'));
        $location  =  $image->getLocation();
        if(Storage::disk('local')->delete([$location.'/thumbnails/'.$image->location, $location.'/'.$image->location])){
            //todo delete croppeds when implement delete image on articles
            $image->delete();
            if($image->galeria!=0){
                Gallery::find($image->galeria)->updateImagesJSON();
            }
        }
        else
            abort(500);
    }

    public function reOrder(Request $request, $gallery){
        if($request->input('prev'))
            $prev = Image::findOrFail($request->input('prev'));
        if($request->input('next'))
            $next = Image::findOrFail($request->input('next'));
        $current = Image::findOrFail($request->input('current'));
        if(isset($next)){
            Image::where('galeria', $gallery)->whereBetween('posicion', [$next->posicion, $current->posicion])->increment('posicion');
            $current->posicion = $next->posicion;
            $current->save();
            $next->posicion = $next->posicion+1;
            $next->save();
        }
        else if(isset($prev)){
            Image::where('galeria', $gallery)->where('posicion', '>', $current->posicion)->decrement('posicion');
            $current->posicion = $prev->posicion+1;
            $current->save();
        }
        return $current;

    }
}
