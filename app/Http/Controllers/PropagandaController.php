<?php

namespace App\Http\Controllers;

use App\Language;
use App\Propaganda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropagandaController extends Controller
{
    //

    public function index($lang){
        $languages = Language::all();
        $ads = Propaganda::where('idioma', $lang)->orderBy('orden')->get();
        return view('propaganda.index', ['languages'=>$languages, 'ads'=>$ads, 'lang'=>$lang]);
    }

    public function toggle($id){
        $prop = Propaganda::findOrFail($id);
        $prop->publicado  = abs($prop->publicado-1);
        $prop->save();
        return redirect(route('propaganda-index', $prop->idioma));
    }

    public function delete($id){
        $prop = Propaganda::findOrFail($id);
        $prop->delete();
        Storage::delete($prop->recurso);
        return redirect(route('propaganda-index', $prop->idioma));
    }

    public function add(Request $request){
        $ad = new Propaganda;
        $languages = Language::all();
        return view('propaganda.edit', ['ad'=>$ad, 'languages'=>$languages]);
    }

    public function edit($id){
        $ad = Propaganda::findOrFail($id);
        $languages = Language::all();
        return view('propaganda.edit', ['ad'=>$ad, 'languages'=>$languages]);
    }

    public function save(Request $request){
        $id = $request->input('idpropaganda');
        if($id){
            $ad = Propaganda::findOrFail($id);
        }
        else
            $ad = new Propaganda;
        if($request->hasFile('recurso')){
            if($id){
                Storage::delete($ad->recurso);
            }
            $path = $request->file('recurso')->store('ads');
            $ad->recurso = $path;
        }
        $ad->idioma = $request->input('idioma');
        $ad->nombres = $request->input('nombres');
        $ad->link = $request->input('link');
        $ad->orden = 100;
        $ad->save();
        return redirect(route('propaganda-index', $ad->idioma));
    }

    public function up($id){
        $ad = Propaganda::findOrFail($id);
        $ad->orden = $ad->orden-1;
        $ad->save();
        return redirect(route('propaganda-index', $ad->idioma));
    }

    public function down($id){
        $ad = Propaganda::findOrFail($id);
        $ad->orden = $ad->orden+1;
        $ad->save();
        return redirect(route('propaganda-index', $ad->idioma));
    }
}
