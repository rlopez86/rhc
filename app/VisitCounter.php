<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VisitCounter extends Model
{
    public static function getTotal($counters = false){
        if(!$counters)
            $counters = VisitCounter::queryCounters();
        return $counters->filter(function ($value, $key){return $value->save_name=='counter';})->first()->save_value;
    }

    public static function getYesterday($counters = false){
        if(!$counters)
            $counters = VisitCounter::queryCounters();
        return $counters->filter(function ($value, $key){return $value->save_name=='yesterday';})->first()->save_value;
    }

    public static function getMaxDay($counters = false){
        if(!$counters)
            $counters = VisitCounter::queryCounters();
        return $counters->filter(function ($value, $key){return $value->save_name=='max_count';})->first()->save_value;
    }

    public static function getOnline($users = false){
        if(!$users)
            $users = VisitCounter::queryUsers();
        return $users->filter(function($value, $key){
            return $value->user_time > Carbon::now()->subMinutes(10)->timestamp;
        })->count();
    }
    public static function getToday($users = false){
        if(!$users)
            $users = VisitCounter::queryUsers();
        return $users->count();
    }

    public static function getCounter(){
        $counters = VisitCounter::queryCounters();
        $users = VisitCounter::queryUsers();
        return [
            'yesterday'=>VisitCounter::getYesterday($counters),
            'online'=>VisitCounter::getOnline($users),
            'today'=>VisitCounter::getToday($users),
            'maxima'=>VisitCounter::getMaxDay($counters),
            'total'=>VisitCounter::getTotal($counters)
        ];
    }

    public static function queryCounters(){
        return DB::table('pcounter_save')->get();
    }

    public static function queryUsers(){
        return DB::table('pcounter_users')->get();
    }
}
