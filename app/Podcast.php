<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    //
    protected $primaryKey = 'idaudio';
    protected $table = 'audios';

    public function program(){
        return $this->belongsTo('App\Program','categoria');
    }
}
