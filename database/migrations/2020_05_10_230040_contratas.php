<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Contratas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratas', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->integer('id_cliente')->unsigned();
            $table->integer('cantidad_prestada');
            $table->integer('comision');
            $table->float('comision_porcentaje');
            $table->integer('cantidad_pagar');
            $table->integer('cantidad_plan_contrata');
            $table->string('tipo_plan_contrata');
            $table->date('fecha_inicio');
            $table->integer('estatus');
            $table->date('fecha_termino');
            $table->float('bonificacion');
            $table->integer('control_pago');
            
            $table->foreign("id_cliente")->references("id")->on("clientes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
