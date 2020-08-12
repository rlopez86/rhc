<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comentarios';
    protected $primaryKey = 'idcomentario';

    public function articleData()
    {
        return $this->belongsTo('App\Article', 'articulo', 'idarticulos');
    }
}
