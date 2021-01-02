<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCambiosToCortes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cortes', function (Blueprint $table) {
            $table->integer("clientes")->default(0);
            $table->integer("contratas")->default(0);
            $table->integer("prestamos_totales")->default(0);
            $table->integer("gastos")->default(0);
            $table->integer("capital_total")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cortes', function (Blueprint $table) {
            $table->dropColumn('clientes');
            $table->dropColumn('contratas');
            $table->dropColumn('prestamos_totales');
            $table->dropColumn('gastos');
            $table->dropColumn('capital_total');
        });
    }
}
