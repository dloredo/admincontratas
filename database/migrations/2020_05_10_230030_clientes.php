<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Clientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->string('nombres');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('telefono_2');

            $table->string('colonia');
            $table->string('ciudad');

            $table->boolean('activo');
            $table->integer('cobrador_id')->unsigned()->nullable();
            $table->date('fecha_registro');
            $table->foreign("cobrador_id")->references("id")->on("usuarios");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @re
     * turn void
     */
    public function down()
    {
        //
    }
}
