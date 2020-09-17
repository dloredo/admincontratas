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
            $table->string('capital_acumulado');
            $table->string('saldo_efectivo');
            $table->string('capital_parcial');
            $table->string('comisiones');
            $table->integer('gastos');
            $table->timestamps();
        });

       
        DB::table("capital")->insert(["capital_acumulado" => 0,
                                    "saldo_efectivo" => 0,
                                    "capital_parcial" => 0,
                                    "comisiones" => 0,
                                    "gastos" => 0
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
