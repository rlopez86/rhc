<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    //
    protected $table = 'correos';
    protected $primaryKey = 'idcorreo';

    public function passFilters(){
        $filters = json_decode(Settings::where('key', config('app.correo_filters_key'))->first()->value);
        foreach ($filters as $filter){
            if(stripos($this->texto, $filter)!==false)
                return false;
        }
        return true;
    }
}
