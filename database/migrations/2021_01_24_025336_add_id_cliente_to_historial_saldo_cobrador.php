<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdClienteToHistorialSaldoCobrador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_saldo_cobrador', function (Blueprint $table) {
            $table->integer("id_cliente")->default(0);
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
            $table->dropColumn('id_cliente');
        });
    }
}
