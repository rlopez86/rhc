<?php

namespace App\Http\Controllers;

use App\Gallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{

    public function index(){
        $galleries = Gallery::orderBy('fechacreacion','desc')->get();
        return view('galleries.index', ['galleries'=>$galleries]);
    }

    public function edit($id){
        $gallery = Gallery::findOrFail($id);
        return view('galleries.edit', ['gallery'=>$gallery]);
    }

    public function add(){
        $gallery = new Gallery;
        return view('galleries.edit', ['gallery'=>$gallery]);
    }

    public function save(Request $request){
        if(!empty($request->input('idgalerias'))){
            $gallery = Gallery::findOrFail($request->input('idgalerias'));
        }
        else {
            $gallery = new Gallery;
            $gallery->location = str_random(5).'-'.substr(str_slug($gallery->nombre), 0, 15);
            mkdir(public_path('galleries/'.$gallery->location));
            mkdir(public_path('galleries/'.$gallery->location.'/thumbnails'));
            $gallery->autor = Auth::user()->id;
            $gallery->publicado = 0;
        }
        $gallery->nombre = $request->input('nombre');
        $gallery->descripcion = $request->input('descripcion');
        $gallery->tags = $request->input('tags');
        $gallery->save();
        if($request->input('idgalerias'))
            return redirect(route('galleries-index'));
        else
            return redirect(route('galleries-edit', $gallery->idgalerias));
    }

    public function toggle($gallery){
        $gallery = Gallery::findOrFail($gallery);
        $gallery->publicado = abs($gallery->publicado - 1);
        $gallery->save();
        return $gallery->publicado;
    }

    public function delete($gallery){
        $gallery = Gallery::findOrFail($gallery);
        Storage::disk('local')->deleteDirectory('galleries/'.$gallery->location);
        $gallery->images()->delete();
        $gallery->delete();
        return redirect(route('galleries-index'));
    }
}
