<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Propaganda extends Model
{
    protected $table = 'propaganda';
    protected $primaryKey = 'idpropaganda';

    public static function getRandomSampling($language, $max=10){
        $propaganda = Propaganda::where('publicado', 1)->where('idioma', $language)->get();
        if($propaganda->count() > $max)
            $propaganda = $propaganda->random($max);
        return $propaganda;
    }

    public static function getPropaganda($language){
        $propaganda = Propaganda::where('publicado', 1)->where('idioma', $language)->orderBy('orden')->get();
        return $propaganda;
    }
}
