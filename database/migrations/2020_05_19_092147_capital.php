<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class Capital extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capital', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->string('capital_total');
            $table->string('capital_neto');
            $table->string('capital_en_prestamo');
            $table->string('comisiones');
            $table->timestamps();
        });

       
        DB::table("capital")->insert(["capital_total" => 0,
                                    "capital_neto" => 0,
                                    "capital_en_prestamo" => 0,
                                    "comisiones" => 0
                                    ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
