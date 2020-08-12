<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncSectionsLanguages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncSectionsLanguages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill secciones_idiomas table with a all pairs section - language possible' ;

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
        $languages = DB::table('idiomas')->select(['ididioma'])->get();
        DB::table('secciones')->select(['idseccion'])->orderBy('idseccion')->chunk(1000, function ($sections) use ($languages) {
            $toinsert = [];
            foreach ($sections as $section) {
                foreach ($languages  as $language)
                    $toinsert[] = ['seccion_id'=>$section->idseccion, 'idioma_id'=>$language->ididioma, 'habilitado'=>1];
            }
            DB::table('secciones_idiomas')->insert($toinsert);
            $this->info('chunk processed');
        });
        $this->info('done');
        return ['done'];
    }
}
