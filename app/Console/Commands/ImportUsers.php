<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fill the new users table from the old one users_old. Will empty the new users table 
    passwords will reset to value of the nick';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = DB::table('users_old')->get();
        if($users){
            DB::table('users')->delete();
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
        }
        return ['done'];
    }
}
