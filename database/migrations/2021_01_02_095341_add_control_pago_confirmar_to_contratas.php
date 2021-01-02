<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddControlPagoConfirmarToContratas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratas', function (Blueprint $table) {
            $table->integer("control_pago_confirmar")->default(0);
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
            $table->dropColumn('control_pago_confirmar');
        });
    }
}
