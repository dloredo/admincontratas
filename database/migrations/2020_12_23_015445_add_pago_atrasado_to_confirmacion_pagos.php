<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPagoAtrasadoToConfirmacionPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('confirmacion_pagos', function (Blueprint $table) {
            $table->boolean('pago_atrasado')->default(false);
            $table->integer('cantidad_pago_atrasado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('confirmacion_pagos', function (Blueprint $table) {
            $table->dropColumn('pago_atrasado');
            $table->dropColumn('cantidad_pago_atrasado');
        });
    }
}
