<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class Article extends Model implements Feedable
{
    protected $table = 'articulos';
    protected $primaryKey = 'idarticulos';

    protected $dates = ['fecha', 'created_at', 'updated_at'];

    public function getIdAttribute(){
        return $this->idarticulos;
    }

    public function sections(){
        return $this->belongsToMany('App\Section', 'articulos_secciones', 'articulo_id', 'seccion_id')->withPivot('orden');
    }

    public function language(){
        return $this->belongsTo('App\Language', 'idioma', 'abrev');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'articulo', 'idarticulos');
    }

    public function imagenFullId(){
        return $this->hasOne('App\Image', 'idimagenes', 'imagen');
    }

    public function imagenFullImagen(){
        if(str_contains($this->imagen, '/'))
            return $this->hasOne('App\Image', 'location', 'imagen');
        else
            return $this->hasOne('App\Image', 'imagen', 'imagen');
    }

    public function imagenFull(){
        if(is_numeric($this->imagen))
            return $this->imagenFullId;
        else
            return $this->imagenFullImagen;
    }

    public function getUrl($section){
        if($this->sections->contains($section))
            return $section->getUrl($this->language).'/'.$this->idarticulos.'-'.str_slug($this->nombre);
        else {
            $cache_sections = Cache::get('sections_graph');
            if (!$cache_sections) {
                Section::updateSectionGraph();
                $cache_sections = Cache::get('sections_graph');
            }
            $sections_collection = json_decode($cache_sections, true);

            foreach ($this->sections as $sect) {
                if (in_array($sect->idseccion, $sections_collection[$section->idseccion]['children']))
                    return $sect->getUrl($this->language) . '/' . $this->idarticulos . '-' . str_slug($this->nombre);
            }

        }
        return false;
    }

    public function getFirstUrl(){
        if($this->sections->count()>0)
            return $this->getUrl($this->sections[0]);
        return '';
    }

    public static function getPortada($language){

        $general = Article::where('idioma', $language)->where('publicado', 1)->where('fecha', '>', Carbon::today()->subDays(3))
        ->orderBy('portada', 'desc')->orderBy('peso', 'asc')->orderBy('fecha', 'desc')->get();


        if($general->count()<12){
            //there are not enough with articles from 3 days to today, changing to the last 12 articles ever
            $general = Article::where('idioma', $language)->where('publicado', 1)
                ->orderBy('fecha', 'desc')->orderBy('portada', 'desc')->orderBy('peso', 'asc');
            $general = $general->limit(12)->get();
        }

        $portada = [];
        $used = [];
        function nextInLine($general, $used){
            foreach ($general as $article){
                if(!in_array($article->idarticulos, $used))
                    return $article;
            }
            return false;
        }

        //Honoring portadas
        foreach ($general as $article){
            if($article->portada == 1 && !isset($portada['section1'])){
                $portada['section1'] = $article;
                $used[] = $article->idarticulos;
            }
            if($article->portada == 2 && !isset($portada['section2'])){
                $portada['section2'] = $article;
                $used[] = $article->idarticulos;
            }
            if($article->portada == 3 && !isset($portada['section3'])){
                $portada['section3'] = $article;
                $used[] = $article->idarticulos;
            }
            if($article->portada == 4 && !isset($portada['section4'])){
                $portada['section4'] = $article;
                $used[] = $article->idarticulos;
            }
        }
        //if some portada fails, fills with next in line
        for ($i = 1; $i <= 4; $i++){
            if(!key_exists('section'.$i, $portada)){
                $article = nextInLine($general, $used);
                $portada['section'.$i] = $article;
                $used[] = $article->idarticulos;
            }
        }
        return $portada;
    }

    public static function getBySectionsLabel($sections, $language, $limit, $skip){
        $cache_sections = Cache::get('sections_graph');
        $sections_collection = json_decode($cache_sections, true);
        $sections_filter = [];
        if(is_string($sections)){
            foreach ($sections_collection as $id=>$section){
                if(isset($section['label']) && $section['label'] == $sections){
                    $sections_filter[] = $id;
                    $sections_filter = array_merge($sections_filter, $section['children']);
                }
            }
        }
        if(is_array($sections)){
            foreach ($sections as $section_label){
                foreach ($sections_collection as $id=>$section){
                    if(isset($section['label']) && $section['label'] == $section_label){
                        $sections_filter[] = $id;
                        $sections_filter = array_merge($sections_filter, $section['children']);
                    }
                }
            }
        }
        $commamd = SectionArticle::with('article')->join('articulos','articulo_id','idarticulos')->where('articulos_secciones.idioma', $language)
            ->where('articulos.publicado',1)
            ->whereIn('seccion_id', $sections_filter)
            ->whereNotIn('articulos.idarticulos',$skip)
            ->orderBy('articulos_secciones.orden', 'asc')
            ->orderBy('articulos_secciones.fecha', 'desc')
            ->orderBy('articulos_secciones.orden', 'desc')
            ->limit($limit);

        $preresults = $commamd->get();
        $results = collect([]);
        foreach ($preresults as $pr){
            $results->push($pr->article);
        }
        return $results;
    }

    public function getSectionsTree($section_id = false){
        if($section_id){
            $section = $this->sections()->where('idseccion', $section_id)->first();
        }
        else{
            $section = $this->sections[0];
        }
        $parents = collect([$section]);
        while($section && $section->parent != 1){
            $parent = $section->fullparent;
            $parents->push($parent);
            $section = $parent;
        }
        return $parents->reverse();
    }

    public static function getNearestInSection($language_abrev, $section_id){
        $articles = DB::table('articulos')->select(['articulos.*', 'articulos_secciones.orden as art_sect_orden', 'articulos_secciones.id as art_sect_id'])
            ->join('articulos_secciones', 'idarticulos', 'articulo_id')
            ->where('articulos.idioma', $language_abrev)
            ->where('articulos_secciones.seccion_id', $section_id)
            ->where('articulos_secciones.orden', '<', 100)
            ->orderBy('articulos_secciones.orden', 'asc')
            ->get();
        return $articles;
    }

    public static function getLatestByBranch($root, $language, $limit=false, $pagination=false){
        $branch = Section::getFullBranch($root, $language);
        $articles = Article::with('sections')->where('idioma', $language)->where('publicado', 1);
        $articles->where(function($query) use($branch){
            
            foreach ($branch as $b){
                $query->orWhere('secciones', 'like', '%,'.$b->idseccion.',%');
            }
        })->orderBy('fecha', 'desc');

        if($pagination)
            return $articles->paginate($pagination);
        if($limit)
            return $articles->limit($limit)->get();
    }

    public static function getLatestGeneral($language, $limit=10, $ignore=[]){
        $latest = Article::where('publicado', 1)
            ->where('idioma', $language)
            ->whereNotIn('idarticulos', $ignore)
            ->orderBy('fecha', 'desc')
            ->limit($limit)
            ->get();
        return $latest;
    }

    public static function getMostVisited($language, $limit=10){
        $most_visited = Article::where('publicado', 1)
            ->where('idioma', $language)
            ->orderBy('visitas', 'desc')
            ->limit($limit)
            ->get();
        return $most_visited;
    }

    /**
     * Obtener articulos relacionados por titulo y palabras claves, se ignora la seccion por defecto
     * el parametro order debe ser 'score' o 'date', cualquier otro valor se utilizara 'score'
     */
    public function getRelated($columns=['articulos.idarticulos', 'articulos.alias', 'articulos.nombre', 'articulos.fecha', 'articulos.seccion'], $section=false, $limit = 10, $order='score'){

        $order != 'date' ? $order = 'score' : $order = 'fecha';
        $title_arr = explode(' ',trim($this->nombre));
        $keys_arr = explode(',', $this->tags);
        $terms = array_merge($title_arr,$keys_arr);

        $str_mysql = '';
        for($i=0; $i<count($terms)-2; $i++ ){
            if(($tc = trim($terms[$i],'"')) != ''){
                $str_mysql.=$tc.',';
            }
        }
        if(($tc = trim($terms[count($terms)-1])) != ''){
            $str_mysql.=$tc;
        }
        else{
            $str_mysql = substr($str_mysql,0,strlen($str_mysql)-2);
        }

        $command = Article::select($columns)->whereRaw(DB::raw('MATCH (articulos.nombre,articulos.tags) AGAINST ("'.$str_mysql.'")'))
        ->join('articulos_secciones', 'idarticulos', 'articulo_id')
        ->join('secciones', 'articulos_secciones.seccion_id', 'idseccion')
        ->where('articulos.publicado', 1)->where('articulos.idioma', $this->idioma);

        if($section)
            $command->where('seccion', $section);
        if($order == 'fecha')
            $command->orderBy('fecha', 'desc');

        $command->limit($limit);
        return $command->get();
    }

    /**
     * @return array|\Spatie\Feed\FeedItem
     */
    public function toFeedItem()
    {
        return FeedItem::create()
            ->id($this->idarticulos)
            ->title($this->nombre)
            ->summary($this->intro)
            ->updated(Carbon::createFromFormat(Carbon::DEFAULT_TO_STRING_FORMAT,$this->fecha))
            ->link($this->getFirstUrl())
            ->author($this->autornombre);

    }

    public static function getAllFeedItems(){
        //return Article::where('idioma', app()->getLocale())->where('fecha', '>=', DB::raw("DATE_SUB(CURDATE(), INTERVAL 1 DAY)"))->orderBy('fecha', 'desc')->get();
        return Article::where('idioma', app()->getLocale())->orderBy('fecha', 'desc')->limit(20)->get(); //TODO CAMBIAR POR LA LINEA SUPERIOR EN PRODUCCION
    }

    public static function getLatestCloud($ignore){
        $ignore_articles = $ignore->pluck('idarticulos');
        $ignore_sections = $ignore->pluck('seccion');
        return Article::whereNotIn('idarticulos', $ignore_articles)->whereNotIn('seccion', $ignore_sections)->where('publicado', 1)
            ->where('idioma', app()->getLocale())->orderBy('fecha', 'desc')->limit(15)->get();
    }

    public static function getLatestWithMedia($language, $limit){
        return Article::where('idioma', $language)->where('video', '!=', '')->orderBy('fecha', 'desc')->limit($limit)->get();
    }

    public static function searchQuery($query, $language, $from=false, $to=false){
        $tagsResults = [['publicado',1],['idioma',$language],['tags','like','%'.$query.'%']];
        $nameResults = [['publicado',1],['idioma',$language],['nombre','like','%'.$query.'%']];
        $command = Article::with('sections')->where($tagsResults)->orWhere($nameResults)->orderBy('fecha','desc');
        if($from)
            $command->whereDate('fecha', '>', Carbon::createFromFormat(trans('messages.date_format'),$from)->format(Carbon::DEFAULT_TO_STRING_FORMAT));
        if($to)
            $command->whereDate('fecha', '<', Carbon::createFromFormat(trans('messages.date_format'), $to)->format(Carbon::DEFAULT_TO_STRING_FORMAT));
        return $command->paginate(15);
    }

    public function getPortadaLocation(){
        $location = '/articles/'.$this->imagen;
        if(str_contains($this->imagen, '/'))
            $location = url($this->imagen);
        else
            if(is_numeric($this->imagen)){
                $location = '/'.$this->imagenFullId->location;
            }
        return asset($location);
    }

    public function getPortadaAlt(){
        if(trim($this->imagenalt)){
            return trim($this->imagenalt);
        }
        else if($this->imagenFull()){
            return trim($this->imagenFull()->alt);
        }
        else{
            return trans('messages.article.image.noalt');
        }
    }
}
