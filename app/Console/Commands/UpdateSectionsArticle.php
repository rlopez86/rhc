<?php

namespace App\Console\Commands;

use App\Section;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateSectionsArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateSectionsArticle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update helper sections table';

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
        //build identifiers
        $sections = Section::all();
        foreach ($sections as $section) {
            DB::table('articulos')->where('seccion', $section->idseccion)->update([
                'secciones'=>','.$section->idseccion.','
            ]);
        }
        return 'done';
    }
}
