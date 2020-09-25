<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->integer("cantidad");
            $table->string("tipo_movimiento");
            $table->integer("id_cobrador")->unsigned();
            $table->timestamps();
            $table->foreign("id_cobrador")->references("id")->on("usuarios");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial');
    }
}
