<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescripcionToHistorialSaldoCobrador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_saldo_cobrador', function (Blueprint $table) {
            $table->string("descripcion")->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_saldo_cobrador', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
}
