<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'idiomas';
    protected $primaryKey = 'ididioma';

    public function sections(){
        return $this->belongsToMany('App\Section', 'secciones_idiomas', 'idioma_id', 'seccion_id');
    }

    public static function regex(){
        $all = Language::where('habilitado', 1)->get();
        $regex = $all->pluck('abrev')->implode('|');
        return $regex;
    }

    public static function abrevs(){
        $all = Language::where('habilitado', 1)->get();
        return $all->pluck('abrev');
    }
}
