<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'imagenes';
    protected $primaryKey = 'idimagenes';

    public function croppeds(){
        return $this->hasMany('App\Image', 'parent');
    }

    public function gallery(){
        return $this->belongsTo('App\Gallery', 'galeria');
    }

    public function getLocation(){
        if($this->gallery){
            return 'galleries/'.$this->gallery->location;
        }
        else
            return 'articles/';
    }
}
