<?php

namespace App\Http\Controllers;

use App\ProgramSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProgramScheduleController extends Controller
{
    public function index($day){
        $programs = ProgramSchedule::where('day',$day)->get();
        return view('schedule.index', ['programs'=>$programs, 'day'=>$day]);
    }

    public function add(Request $request){
        $program = new ProgramSchedule;
        return view('schedule.edit', ['program'=>$program]);
    }

    public function edit($id){
        $program = ProgramSchedule::findOrFail($id);
        return view('schedule.edit', ['program'=>$program]);
    }

    public function save(Request $request){
        $request->validate([
            'day'=>'required|in:0,1,2,3,4,5,6',
            'hour'=>'required|date_format:h:i A',
            'name'=>'required'
        ]);
        if($request->input('id')){
            $program = ProgramSchedule::find($request->input('id'));
        }
        else
            $program = new ProgramSchedule;
        $program->name= $request->input('name');
        $program->day = $request->input('day');
        $program->hour = Carbon::createFromFormat('h:i A', $request->input('hour'))->format('H:i');
        $program->description = $request->input('description');
        $program->save();
        return redirect(route('schedule-index', $program->day));
    }

    public function toggle($id){
        $program = ProgramSchedule::findOrFail($id);
        $program->habilitado  = abs($program->habilitado-1);
        $program->save();
        return redirect(route('schedule-index', $program->day));
    }

    public function delete($id){
        $program = ProgramSchedule::findOrFail($id);
        $program->delete();
        return redirect(route('schedule-index', $program->day));
    }

}
