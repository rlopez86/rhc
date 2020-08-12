<?php

namespace App\Http\Controllers;

use App\Article;
use App\Image;
use App\Language;
use App\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    private $columnsMap;

    public function __construct(){
        $this->columnsMap = ['','','nombre', 'fecha', 'autornombre', 'sections', 'idioma'];
    }

    public function index(){
        $articles = Article::with(['sections', 'language'])->orderBy('fecha', 'desc')->take(10)->get();
        $sections  = Section::all();
        $languages = Language::all();
        $count = Article::count();
        return view('articles.index', ['articles'=>$articles, 'count'=>$count, 'sections'=>$sections, 'languages'=>$languages]);
    }

    public function add(){
        $article = new Article;
        $languages = Language::all();
        return view('articles.edit', ['article'=>$article, 'languages'=>$languages]);
    }

    public function edit(Article $article){
        $languages = Language::all();
        return view('articles.edit', ['article'=>$article, 'languages'=>$languages]);
    }

    public function delete(Article $article){
        //TODO include trazas
        $article->sections()->detach();
        $article->delete();
        return 1;
    }

    public function toggle(Article $article){
        $article->publicado = abs($article->publicado - 1);
        
        $article->save();
       
        return $article->publicado;
    }

    public function organize(Request $request){
        $language = $request->input('language', 'es');
        $section = $request->input('section', 7);
        $sections  = Section::where('idseccion', '!=', 1)->get();
        $languages = Language::all();
        $articles = Article::select('articulos.*', 'articulos_secciones.orden')->where('articulos.idioma', $language)
            ->join('articulos_secciones', 'idarticulos', 'articulo_id')
            ->where('seccion_id', $section)
            ->orderBy('articulos_secciones.orden')
            ->orderBy('articulos.fecha', 'desc')
            ->limit(40)->get();
        $articulos_vigentes = 0;
        foreach ($articles as $art) {
            if($art->fecha > Carbon::now()->subDays(3))
                $articulos_vigentes++;
        }
        return view('articles.organize', ['sections'=>$sections, 'languages'=>$languages, 'articles'=>$articles, 'language'=>$language, 'section'=>$section, 'articulos_vigentes'=>$articulos_vigentes]);
    }

    public function front(Article $article, Request $request){
        $position = $request->input('position');
        $article->portada = $position;
        $article->save();
        return json_encode([$position]);
    }


    /**
     * @param Article $article
     * @param Request $request
     * @return string
     *
     * we will interchange the order with the superior article
     * if all articles has a value of 100 then we will ensure that this article is first by setting his value to 1
     */
    public function up(Article $article, Request $request){
        if($article->fecha < Carbon::now()->subDays(3))
            return json_encode(['ERROR']);
        $section = $request->input('section');
        $language = $request->input('language');
        $pivot = $article->sections()->where('idseccion', $section)->first()->pivot;
        $near = DB::table('articulos_secciones')
            ->join('articulos', 'idarticulos', 'articulo_id')
            ->where('articulos.idioma', $language)
            ->where('seccion_id', $section)
            ->where('orden', '<', $pivot->orden)
            ->orderBy('orden', 'desc')
            ->first();
        if($near){
            $orden = $pivot->orden;
            $pivot->orden = $near->orden;
            $pivot->save();
            DB::table('articulos_secciones')
                ->where('articulo_id',$near->articulo_id)
                ->where('seccion_id', $section)
                ->update(['orden'=>$orden]);
        }
        else{
            $pivot->orden = 1;
            $pivot->save();
        }
        $near->orden = $orden;
        $article->orden = $pivot->orden;
        return json_encode([$near, $article]);
    }
    public function orden(Article $article, Request $request){
        
        $position = $request->input('position');
        $section = $request->input('section');
        $language = $request->input('language');
        $old_pos = DB::table('articulos_secciones')
        ->join('articulos', 'idarticulos', 'articulo_id')
        ->where('idarticulos',$article->idarticulos)
        ->where('articulos.idioma', $language)
        ->where('seccion_id', $section)
        ->first()->orden;
        if($article->fecha < Carbon::now()->subDays(3))
            return json_encode(['ERROR',$old_pos,$article]);
        
        $add = (($old_pos - $position) < 0) ? -1:1;
        
        $articles_to_update_order = DB::table('articulos_secciones')
        ->join('articulos', 'idarticulos', 'articulo_id')
        ->where('articulos.idioma', $language)
        ->where('seccion_id', $section)
        ->whereBetween('orden', ($position > $old_pos)?[$old_pos+1,$position]:[$position,$old_pos-1])
        ->increment('orden',$add);
        
        //$article_seccion->orden = $position;
        //$article_seccion->save;
        DB::table('articulos_secciones')
        ->join('articulos', 'idarticulos', 'articulo_id')
        ->where('idarticulos',$article->idarticulos)
        ->where('articulos.idioma', $language)
        ->where('seccion_id', $section)
        ->update(['orden'=>$position]);

        $articles_to_change_order = DB::table('articulos_secciones')
        ->join('articulos', 'idarticulos', 'articulo_id')
        ->where('articulos.idioma', $language)
        ->where('seccion_id', $section)
        ->whereBetween('orden', ($position > $old_pos)?[$old_pos,$position]:[$position,$old_pos])
        ->orderBy('orden','desc')
        ->get();
        return json_encode([$articles_to_change_order , $old_pos, $article]);

    }

    public function down(Article $article, Request $request){
        if($article->fecha < Carbon::now()->subDays(3))
            return json_encode(['ERROR']);
        $section = $request->input('section');
        $language = $request->input('language');
        $pivot = $article->sections()->where('idseccion', $section)->first()->pivot;
        $near = DB::table('articulos_secciones')
            ->join('articulos', 'idarticulos', 'articulo_id')
            ->where('articulos.idioma', $language)
            ->where('seccion_id', $section)
            ->where('orden', '>', $pivot->orden)
            ->orderBy('orden', 'asc')
            ->first();
        if($near){
            $orden = $pivot->orden;
            $pivot->orden = $near->orden;
            $pivot->save();
            DB::table('articulos_secciones')
                ->where('articulo_id',$near->articulo_id)
                ->where('seccion_id', $section)
                ->update(['orden'=>$orden]);
        }
        else{
            $pivot->orden = 1;
            $pivot->save();
        }
        $near->orden = $orden;
        $article->orden = $pivot->orden;
        return json_encode([$near, $article]);
    }

    public function save(Request $request){
        if($request->input('idarticulos')){
            $article = Article::findOrFail($request->input('idarticulos'));
            $article->editeddate = Carbon::now();
            $article->editedautor = $request->user()->id;
        }
        else{
            $sections = $request->input('seccion');
            $article = new Article;
            $article->fecha = Carbon::now();
            $article->autor = $request->user()->id;
            $article->autornombre = $request->user()->name;
            $article->seccion = $sections[0];
            $article->visitas = 0;
            $article->peso = 100;
            foreach ($sections as $s){
                print($s);
                print("Que esra pasando aqui");
                $to_update = Article::getNearestInSection($request->input('idioma'), $s);
                //print_r($to_update); exit();
                DB::table('articulos_secciones')
                    ->whereIn('id', $to_update->pluck('art_sect_id'))
                    ->increment('orden');
            }
        }
        $article->peso = 1;
        $article->imagen = $request->input('imagen');
        $article->imagenalt = $request->input('imagenalt');
        $article->audios = $request->input('audios');
        $article->video = $request->input('video', $article->video);
        $article->idioma = $request->input('idioma');
        $article->nombre = $request->input('nombre');
        $article->texto = $request->input('processed-text', $request->input('texto'));
        $article->tags = $request->input('tags');
        $article->metadesc = $request->input('metadesc');
        $article->intro = $request->input('intro');
        $article->alias = str_slug($article->nombre);
        $article->publicado = 0;
        $article->secciones =  ','.implode(',', $request->input('seccion')).',';
        $article->save();
        $sections_sync = [];
        foreach ($request->input('seccion') as $s)
            $sections_sync[$s] = ['orden'=>1, 'idioma'=>$article->idioma, 'publicado'=>0];
        $article->sections()->sync($sections_sync);
        
        return redirect(route('articles-index'));
    }

    public function data(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search');
        $order = $request->input('order');
        $from = $request->input('from', '');
        $to = $request->input('to', '');
        $pub = $request->input('pub', -1);
        $section = $request->input('section', 0);
        $language = $request->input('language', 0);
        $recordsTotal = Article::query()->count();

        $data = Article::with(['sections', 'language'])->orderBy($this->columnsMap[$order[0]['column']], $order[0]['dir']);
        if($search['value']){
            $data->where(function($query) use ($search) {
                $query->where('nombre', 'like', '%'.$search['value'].'%');
                $query->orWhere('autornombre', 'like', '%'.$search['value'].'%');
                $query->orWhere('fecha', 'like', '%'.$search['value'].'%');
            });
        }
        if($from){
            $dfrom = Carbon::createFromFormat('d/m/Y', $from);
            $data->where('fecha', '>', $dfrom->format('Y-m-d'));
        }
        if($to){
            $dto = Carbon::createFromFormat('d/m/Y', $to);
            $data->where('fecha', '<', $dto->format('Y-m-d'));
        }
        if($pub != -1){
            $data->where('publicado', $pub);
        }
        if($language){
            $data->where('idioma', $language);
        }
        if($section){
            $data->where('seccion',$section); //TODO the column seccion will be erased in the future
            $data->orWhere('secciones', 'like', '%,'.$section.',%');
        }

        $recordsFiltered = $data->count();
        $data->skip($start)->take($length);
        //return $data->toSql();
        return [
            'draw'=>$draw,
            'data'=>$this->toDataRow($data->get()),
            'recordsFiltered'=>$recordsFiltered,
            'recordsTotal'=>$recordsTotal,
        ];
    }

    protected function toDataRow($articles){
        $data = [];
        foreach ($articles as $article){
            $data[] = [
                view('articles.cell_m', ['article'=>$article])->render(),
                view('articles.cell_a', ['article'=>$article])->render(),
                view('articles.cell_nombre', ['article'=>$article])->render(),
                $article->fecha,
                $article->autornombre,
                $article->sections->implode('nombre', ','),
                $article->idioma
            ];
        }
        return $data;
    }
}
