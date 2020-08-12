<?php

namespace App\Http\Controllers;

use App\Language;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    public function index(){
        $sections = Section::Tree(false,true);
        $languages = Language::all();
        return view('sections.index', ['sections'=>$sections, 'languages'=>$languages]);
    }

    public function add(){
        $section = new Section;
        $sections = Section::all();
        $languages = Language::all();
        return view('sections.edit', ['section'=>$section, 'sections'=>$sections, 'languages'=>$languages]);
    }

    public function languageToggle($id, $lang){
        $pivot = DB::table('secciones_idiomas')->where(['seccion_id'=>$id, 'idioma_id'=>$lang])->first();
        if($pivot){
            DB::table('secciones_idiomas')->where('id', $pivot->id)->delete();
        }
        else{
            DB::table('secciones_idiomas')->insert(['seccion_id'=>$id, 'idioma_id'=>$lang]);
        }
        return redirect(route('sections-index'));
    }

    public function toggle($id){
        $section = Section::findOrFail($id);
        $section->habilitado  = abs($section->habilitado-1);
        $section->save();
        Section::updateCache();
        return redirect(route('sections-index'));
    }

    public function edit($id){
        $section = Section::findOrFail($id);
        $sections = Section::all();
        $languages = Language::all();
        return view('sections.edit', ['section'=>$section, 'sections'=>$sections, 'languages'=>$languages]);
    }

    public function save(Request $request){
        $request->validate([
            'nombre'=>'required',
            'label'=>'required',
        ]);
        if($request->input('idseccion')){
            $section = Section::findOrFail($request->input('idseccion'));
        }
        else{
            $section = new Section;
        }
        if($request->hasFile('banner')){
            Storage::disk('local')->delete($section->banner);
            $section->banner = $request->file('banner')->store('banners');
        }
        $section->nombre = $request->input('nombre');
        $section->label = $request->input('label');
        $section->parent = $request->input('parent');
        $section->save();
        $section->languages()->sync($request->input('languages'));
        Section::updateCache();
        return redirect(route('sections-index'));
    }

    public function delete($id){
        $section = Section::findOrFail($id);
        DB::table('articulos_secciones')->where('seccion_id',$id)->delete();
        DB::table('articulos')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('articulos_secciones')
                    ->whereRaw('articulos.idarticulos = articulos_secciones.articulo_id');
            })
            ->delete();
        $section->delete();
        Section::updateCache();
        return redirect(route('sections-index'));
    }

    public function testCache(){
        Section::updateCache();
    }

    public function freeze($id){
        $section = Section::findOrFail($id);
        $section->freeze  = abs($section->freeze-1);
        $section->save();
        return redirect(route('sections-index'));
    }
}
