<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmacionPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmacion_pagos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->integer('id_pago_contrata')->unsigned();
            $table->integer('id_cobrador')->unsigned();
            $table->integer('cantidad_pagada');
            $table->integer('adeudo');
            $table->integer('adelanto');
            $table->integer('estatus');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('confirmacion_pagos');
    }
}
