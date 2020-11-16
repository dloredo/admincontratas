<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class PruebaCronjob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prueba:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba de comnando para hosting';

    protected $table = "prueba_crontab";
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

        $object = DB::table($this->table)->where("id",1)->first();

        $counter = $object->counter+1;

        DB::table($this->table)->where("id",1)->update([
            "counter" => $counter,
            "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
        ]);

        echo "Jaló";
    }
}
