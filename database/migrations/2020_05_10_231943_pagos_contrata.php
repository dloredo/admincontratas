<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PagosContrata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_contratas', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->integer('id_contrata')->unsigned();
            $table->date('fecha_pago');
            $table->integer('cantidad_pagada');
            $table->integer('adeudo');
            $table->integer('adelanto');
            $table->integer('estatus');
            
            $table->foreign("id_contrata")->references("id")->on("contratas");
        });
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
