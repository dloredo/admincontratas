<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DiasPagosContrata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_pagos_contrata', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->integer('id_contrata')->unsigned();
            $table->string('dias');
            
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
