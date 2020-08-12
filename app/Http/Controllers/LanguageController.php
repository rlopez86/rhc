<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    public function index(){
        $languages = Language::all();
        return view('languages.index', ['languages'=>$languages]);
    }

    public function add(){
        $language = new Language;
        return view('languages.edit', ['language'=>$language]);
    }

    public function enable($id){
        $language = Language::findOrFail($id);
        if($language->habilitado)
            $language->habilitado = 0;
        else
            $language->habilitado = 1;
        $language->save();
        return redirect(route('languages-index'));
    }

    public function defaulted($id){
        DB::table('idiomas')->update(['default'=>0]);
        $language = Language::findOrFail($id);
        $language->default = 1;
        $language->save();
        return redirect(route('languages-index'));
    }

    public function edit($id){
        $language = Language::findOrFail($id);
        return view('languages.edit', ['language'=>$language]);
    }

    public function save(Request $request){
        $request->validate([
            'idioma'=>'required',
            'abrev'=>'required',
        ]);
        if($request->input('ididioma'))
            $language = Language::findOrFail($request->input('ididioma'));
        else
            $language = new Language;
        $language->idioma = $request->input('idioma');
        $language->abrev = $request->input('abrev');
        $language->save();
        /* TODO discutir
        if($request->hasFile('translation')){
            $file = $request->file('translation');
            if($file->getMimeType() == 'text/x-php'){
                $file->move(base_path('resources/lang/'.$language->abrev), $file->getClientOriginalName());
            }
        }
        */
        return redirect(route('languages-index'));
    }

    public function download(){
        return response()->download(base_path('resources/lang/es/messages.php'));
    }

    public function sections(Request $request){
        $lang = $request->input('lang');
        $language = Language::where('abrev', $lang)->first();
        return $language->sections()->where('habilitado', 1)->get();
    }
}
