<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_saldos', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("cargos");
            $table->integer("abonos");
            $table->integer("saldo");
            $table->integer("id_cobrador")->unsigned();
            $table->foreign("id_cobrador")->references("id")->on("usuarios");
            $table->date("fecha");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_saldos');
    }
}
