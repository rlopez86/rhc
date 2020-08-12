<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProgramSchedule extends Model
{
    protected $table = 'schedule';

    public static function getTodaySchedule(){
        $current_day = Carbon::today()->dayOfWeek;
        $next_day = $current_day < 6 ? $current_day + 1 : 0;
        $scheduleToday = ProgramSchedule::where('habilitado', 1)->where('day', $current_day)->orderBy('hour')->get();
        $scheduleTomorrow = ProgramSchedule::where('habilitado', 1)->where('day', $next_day)->orderBy('hour')->get();
        return ['today'=>$scheduleToday, 'tomorrow'=>$scheduleTomorrow];
    }
}
