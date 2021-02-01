<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmacionPagoAnualidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirmacion_pago_anualidads', function (Blueprint $table) {
            $table->integer("id_cobrador");
            $table->integer("id_contrata");
            $table->date("fecha_anualidad");     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('confirmacion_pago_anualidads');
    }
}
