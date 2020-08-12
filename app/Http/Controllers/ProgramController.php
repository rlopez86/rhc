<?php

namespace App\Http\Controllers;

use App\Language;
use App\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    //

    public function index($lang){
        $programs = Program::where('idioma', $lang)->orderBy('orden')->get();
        $languages = Language::all();
        return view('podcasts.programs.index', ['programs'=>$programs, 'languages'=>$languages, 'lang'=>$lang]);
    }

    public function add(){
        $program = new Program;
        $languages = Language::all();
        return view('podcasts.programs.edit', ['program'=>$program, 'languages'=>$languages]);
    }

    public function edit($id){
        $program = Program::findOrFail($id);
        $languages = Language::all();
        return view('podcasts.programs.edit', ['program'=>$program, 'languages'=>$languages]);
    }

    public function save(Request $request){
        $id = $request->input('idprograma');
        if($id)
            $program = Program::findOrfail($id);
        else
            $program = new Program;
        $program->idioma = $request->input('idioma');
        $program->nombre = $request->input('nombre');
        $program->extra = $request->input('extra');
        $program->save();
        return redirect(route('programs-index', $program->idioma));
    }

    public function delete($id){
        $program = Program::findOrfail($id);
        $program->audios()->delete();
        $program->delete();
        return redirect(route('programs-index', $program->idioma));
    }

    public function toggle($id){
        $prop = Program::findOrFail($id);
        $prop->habilitado  = abs($prop->habilitado-1);
        $prop->save();
        return redirect(route('programs-index', $prop->idioma));
    }

    public function up($id){
        $program = Program::findOrFail($id);
        $near = Program::where('orden', '<', $program->orden)->where('idioma', $program->idioma)->orderBy('orden', 'desc')->first();
        if($near){
            $orden = $program->orden;
            $program->orden = $near->orden;
            $program->save();
            $near->orden = $orden;
            $near->save();
        }
        else{
            $program->orden = 1;
            $program->save();
        }
        return redirect(route('programs-index', $program->idioma));
    }

    public function down($id){
        $program = Program::findOrFail($id);
        $near = Program::where('orden', '>', $program->orden)->where('idioma', $program->idioma)->orderBy('orden')->first();
        if($near){
            $orden = $program->orden;
            $program->orden = $near->orden;
            $program->save();
            $near->orden = $orden;
            $near->save();
        }
        else{
            $program->orden = 100;
            $program->save();
        }
        return redirect(route('programs-index', $program->idioma));
    }

}
