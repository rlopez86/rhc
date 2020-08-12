<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function permissions(){
        return $this->belongsToMany('App\Permission', 'users_permits','user_id', 'permit_id');
    }

    public function redactor(){
        return $this->hasOne('App\Redactor', 'users_iduser');
    }

    public function languages(){
        if($this->redactor)
            return collect(json_decode($this->redactor->idiomas));
        else
            return collect([]);
    }

    public function articles(){
        return $this->hasMany('App\Article', 'autor');
    }
}
