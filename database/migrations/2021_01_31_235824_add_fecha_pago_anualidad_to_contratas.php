<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaPagoAnualidadToContratas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratas', function (Blueprint $table) {
            $table->date("fecha_pago_anualidad")->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratas', function (Blueprint $table) {
            $table->dropColumn('fecha_pago_anualidad');
        });
    }
}
