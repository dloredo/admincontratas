<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPagoAnualidadToContratas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratas', function (Blueprint $table) {
            $table->integer("pago_anualidad")->default(0);
            $table->dropColumn('fecha_pago_anualidad');
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
            $table->dropColumn('pago_anualidad');
        });
    }
}
