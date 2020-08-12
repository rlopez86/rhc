<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncArticlesSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncArticlesSections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will fill table articulos_secciones from the column section on articulos table, 
    allowing articulos to has more sections, the column section will not be deleted';

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
        DB::table('articulos')->select(['idarticulos', 'seccion', 'peso', 'idioma', 'fecha', 'publicado'])
            ->orderBy('idarticulos')->chunk(1000, function ($articles) {
            $toinsert = [];
            foreach ($articles as $article) {
                $toinsert[] = ['articulo_id'=>$article->idarticulos, 'seccion_id'=>$article->seccion,
                    'orden'=>$article->peso, 'idioma'=>$article->idioma, 'fecha'=>$article->fecha, 'publicado'=>$article->publicado];
            }
            DB::table('articulos_secciones')->insert($toinsert);
            $this->info('1000 articles processed');
        });
        $this->info('1000 articles processed');
        return ['done'];
    }
}
