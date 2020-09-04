<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFechasDesestimadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fechas_desestimadas', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->integer("anio");
            $table->date("fecha_inicio");
            $table->date("fecha_termino");
            $table->string("descripcion");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fechas_desestimadas');
    }
}
