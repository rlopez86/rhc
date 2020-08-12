<?php

namespace App\Http\Controllers;

use App\Language;
use App\Podcast;
use App\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PodcastController extends Controller
{
    //

    public function index(Request $request){
        $program_id = $request->input('categoria', 0);
        $lang_abrev = $request->input('idioma', 0);
        $command = Podcast::with('program')->orderBy('fecha');
        if($program_id){
            $command->where('categoria', $program_id);
        }
        if($lang_abrev){
            $command->where('idioma', $lang_abrev);
        }
        $podcasts = $command->get();
        $programs = Program::all();
        $languages = Language::all();
        return view('podcasts.audios.index',
            ['podcasts'=>$podcasts, 'programs'=>$programs, 'languages'=>$languages, 'program_id'=>$program_id, 'l_abrev'=>$lang_abrev]);
    }

    public function toggle($id){
        $podcast = Podcast::findOrFail($id);
        $podcast->publicado  = abs($podcast->publicado-1);
        $podcast->save();
        return redirect(route('podcasts-index').'?categoria='.$podcast->categoria.'&idioma='.$podcast->idioma);
    }

    public function delete($id){
        $podcast = Podcast::findOrfail($id);
        if(!$podcast->externo){
            Storage::delete($podcast->location);
        }
        $podcast->delete();
        return redirect(route('podcasts-index').'?categoria='.$podcast->categoria.'&idioma='.$podcast->idioma);
    }

    public function add(){
        $podcast = new Podcast;
        $languages = Language::all();
        $programs = Program::all();
        return view('podcasts.audios.edit', ['podcast'=>$podcast, 'languages'=>$languages, 'programs'=>$programs]);
    }

    public function edit($id){
        $podcast = Podcast::findOrFail($id);
        $languages = Language::all();
        $programs = Program::all();
        return view('podcasts.audios.edit', ['podcast'=>$podcast, 'languages'=>$languages, 'programs'=>$programs]);
    }

    public function orphaned(){
        $locals = Podcast::where('externo', 0)->get()->pluck('location');
        $storaged = Storage::files('audios');
        $orphaned = [];
        foreach($storaged as $st){
            if(!$locals->contains($st))
                $orphaned[] = $st;
        }
        return $orphaned;
    }

    public function save(Request $request){
        $id = $request->input('idaudio');
        if($id)
            $podcast = Podcast::findOrfail($id);
        else
            $podcast = new Podcast;
        $podcast->categoria = $request->input('categoria');
        $podcast->idioma = $request->input('idioma');
        $podcast->fecha = Carbon::createFromFormat('d/m/Y', $request->input('fecha'));
        $podcast->nombre = $request->input('nombre');
        $podcast->descripcion = $request->input('descripcion');
        if($request->input('location_ext')){
            $podcast->location = $request->input('location_ext');
            $podcast->externo = 1;
        }
        else{
            $podcast->location = $request->input('location_local');
            $podcast->externo = 0;
        }

        $podcast->save();
        return redirect(route('podcasts-index').'?categoria='.$podcast->categoria.'&idioma='.$podcast->idioma);
    }

}
