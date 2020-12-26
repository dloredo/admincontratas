<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialSaldoCobradorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_saldo_cobrador', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments("id");
            $table->integer("cantidad"); // Cantidad que se le restara o sumara al saldo
            $table->integer("id_cobrador")->unsigned(); //Para saber a que cobrador pertenece
            $table->foreign("id_cobrador")->references("id")->on("usuarios");

            $table->string("tipo"); // Tipo de saldo, como cargo o abono, o lo que cobro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_saldo_cobrador');
    }
}
