<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionArticle extends Model
{
    protected $table = 'articulos_secciones';

    public function article(){
        return $this->belongsTo('App\Article', 'articulo_id', 'idarticulos');
    }
}
