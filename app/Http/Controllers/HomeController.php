<?php

namespace App\Http\Controllers;

use App\ProgramSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{

    public function importUsers(){
        $users = DB::table('users_old')->get();
        foreach ($users as $user){
            try{
                DB::table('users')->insert([
                    'id'=>$user->iduser,
                    'name'=>$user->nombre.' '.$user->apellido1.' '.$user->apellido2,
                    'nick'=>$user->nick,
                    'password'=>bcrypt($user->nick),
                    'habilitado'=>$user->habilitado
                ]);
            }
            catch(\Exception $e){
                print $e->getMessage();
                //exit();
            }
        }
        return 'ok';
    }

    public function anything(){
        return 'OK';
    }

    public function showRoutes(){

    }

    public function Schedule(Request $request){
        return ProgramSchedule::getTodaySchedule();
    }
}
