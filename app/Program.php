<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programas';
    protected $primaryKey = 'idprograma';

    public function audios(){
        return $this->hasMany('App\Podcast','categoria');
    }
}
