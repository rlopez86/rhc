<?php

namespace App\Http\Controllers;

use App\Language;
use App\Ribbon;

use Illuminate\Http\Request;

class RibbonController extends Controller
{
    public function index(){
        $ribbons = Ribbon::all();
        return view('ribbon.index', ['ribbons'=>$ribbons]);
    }

    public function add(){
        $ribbon = new Ribbon;
        $languages = Language::all();
        return view('ribbon.edit', ['ribbon'=>$ribbon, 'languages'=>$languages]);
    }

    public function toggle($id){
        $ribbon = Ribbon::findOrFail($id);
        $ribbon->publicado  = abs($ribbon->publicado-1);
        $ribbon->save();
        return redirect(route('ribbon-index'));
    }

    public function delete($id){
        $ribbon = Ribbon::findOrFail($id);
        $ribbon->delete();
        return redirect(route('ribbon-index', $ribbon->idioma_id));
    }

    public function edit($id){
        $ribbon = Ribbon::findOrFail($id);
        $languages = Language::all();
        return view('ribbon.edit', ['ribbon'=>$ribbon, 'languages'=>$languages]);
    }

    public function save(Request $request){
        $id = $request->input('id');
        if($id){
            $ribbon = Ribbon::findOrFail($id);
        }
        else
            $ribbon = new Ribbon;
        $ribbon->idioma = $request->input('idioma');
        $ribbon->label = $request->input('label');
        $ribbon->position = $request->input('position');
        $ribbon->html = $request->input('html');
        $ribbon->save();
        return redirect(route('ribbon-index'));
    }
}
