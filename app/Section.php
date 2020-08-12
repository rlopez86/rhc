<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Section extends Model
{

    protected $table = 'secciones';
    protected $primaryKey = 'idseccion';

    public function languages(){
        return $this->belongsToMany('App\Language', 'secciones_idiomas', 'seccion_id', 'idioma_id');
    }

    public function fullParent(){
        return $this->belongsTo('App\Section', 'parent');
    }

    public function children(){
        return $this->hasMany('App\Section', 'parent');
    }

    public static function tree($language, $ignore_flag=false){

        if($ignore_flag)
            $sections = Section::all();
        else
            $sections = Section::where('habilitado', 1)->whereHas('languages', function($query) use($language){
                $query->where('abrev', $language);
            })->get();
        $references = [];
        foreach ($sections as $section){
            $references[$section->idseccion] = ['children'=>[], 'data'=>$section];
        }
        foreach ($references as $reference){
            $references[$reference['data']->parent]['children'][$reference['data']->idseccion] = &$references[$reference['data']->idseccion];
        }
        //print_r(json_encode(array_keys($references)));exit();
        if(key_exists(0, $references)){
            return $references[0];
        }
        return current($references);
    }

    public function getUrl($language = false, $full=true){
        if(!$language)
            $language = Language::where('abrev', App::getLocale())->first();
        $raw_uri = '';
        $parent = $this->parent;
        $current = $this;
        while($parent!=0){
            $raw_uri = $current->label.'/'.$raw_uri;
            $current = $current->fullParent;
            $parent = $current->parent;
        }
        if(!$language->default){
            $raw_uri = $language->abrev.'/'.$raw_uri;
        }
        if($full)
            return url($raw_uri);
        return $raw_uri;
    }

    public static function getFullBranch($section, $language){
        $sections = Section::where('habilitado', 1)->whereHas('languages', function($query) use($language){
            $query->where('abrev',$language);
        })->get();
        $out = collect([$section]);
        $pool = collect([$section->idseccion]);
        while($pool->count() > 0){
            $current = [];
            foreach ($sections as $s){
                if($pool->contains($s->parent)){
                    $current[] = $s->idseccion;
                    $out->push($s);
                }
            }
            $pool = collect($current);
        }
        return $out;
    }

    public static function getNewsSections($language){
        return Section::where('habilitado', 1)->where('parent', 3)->whereHas('languages', function ($query) use($language){
            $query->where('abrev',$language);
        })->get();
    }

    public static function routes($language=false){
        $uris = json_decode(file_get_contents(base_path('resources/sections.json')));
        $router = App::make('router');
        $prefix = '';
        if($language)
            $prefix = '/{language?}';
        foreach ($uris as $uri){
            $controller = $uri->controller;
            if(!$controller)
                $controller = 'PortalController@'.str_slug($uri->label);
            //$router->get($prefix.$uri->uri, $controller);
            //print($prefix.$uri->uri.'{article?}     ');
            $router->get($prefix.$uri->uri.'{article?}', $controller)->where('language', Language::regex());
        }
    }

    public static function updateCache(){
        $sections = Section::where('habilitado', 1)->where('label','!=', '')->get();
        $parents = collect([]);
        $processed = collect([]);
        foreach ($sections as $section){
            if($section->children->count() == 0){
                $processed[$section->idseccion] = $section;
            }
            else{
                $parents[$section->idseccion] = $section;
            }
        }
        $flag = true;
        while($parents->count()>0 && $flag){
            $flag = false;
            foreach ($processed as $proc){
                if($parents->keys()->contains($proc->parent)){
                    $flag = true;
                    $processed[$proc->parent] = $parents[$proc->parent];
                    $parents->forget($proc->parent);
                }
            }
        }
        foreach ($parents as $par)
            $processed[$par->id] = $par;
        $file = collect([]);
        $language = Language::where('abrev', config('app.locale'))->first();
        foreach ($processed as $section){
            $file->push(['uri'=>'/'.$section->getUrl($language, false), 'controller'=>$section->controlador, 'label'=>$section->label]);
        }
        for ($i=1; $i < count($file); $i++) { 
            for ($j=0; $j < $i; $j++) { 
                if(count(explode('/',$file[$i]['uri'])) > count(explode('/',$file[$j]['uri']))){
                    $t = $file[$i];
                    $file[$i]=$file[$j];
                    $file[$j] = $t;
                }
            }
        }
        for ($i=0; $i < count($file); $i++) { 
            echo $file[$i]["uri"]."--";
        }
        $cache = fopen(base_path('resources/sections.json'), 'wb');
        fwrite($cache, json_encode($file));
        fclose($cache);
        Section::updateSectionGraph();
        return file_get_contents(base_path('resources/sections.json'));
    }

    public static function getByPath($path){
        $parts = explode('/', $path);
        if(count($parts) == 1 || (count($parts) == 2 && $parts[0] == '')){ //A path can come in the form "/5-heroes", the first part is an empty string
            $url = $parts[0];
            if(!$url) //empty, go for second part
                $url = $parts[1];
            return Section::where('label', $url)->first();
        }
        else{
            $urlparent = $parts[count($parts)-2];
            $url = $parts[count($parts)-1];
            $parent = Section::where('label', $urlparent)->first();
            return Section::where('label', $url)->where('parent', $parent->idseccion)->first();
        }
    }

    public static function updateSectionGraph(){
        $graph = collect([]);
        $sections = Section::where('habilitado', 1)->get();
        foreach ($sections as $s){
            if(!$graph->has($s->idseccion)){
                $graph->put($s->idseccion, collect(['children'=>collect([]), 'parents'=>collect([])]));
            }
            $graph[$s->idseccion]->put('label', $s->label);
            $graph[$s->idseccion]['parents']->push($s->parent);

            if(!$graph->has($s->parent)){
                $graph->put($s->parent, collect(['children'=>collect([]), 'parents'=>collect([])]));
            }
            $graph[$s->parent]['children']->push($s->idseccion);
        }
        //update indirect parents
        foreach ($graph as $id=>$s){
            if($id == 0)
                continue;
            $current = $s;
            while($current['parents'][0] != 0){
                $graph[$id]['parents']->push($graph[$current['parents'][0]]['parents'][0]);
                $current = $graph[$current['parents'][0]];
            }
        }
        Cache::forever('sections_graph', json_encode($graph));
    }

    public static function getSearchSection(){
        return Section::where('label', 'search')->first();
    }

}
