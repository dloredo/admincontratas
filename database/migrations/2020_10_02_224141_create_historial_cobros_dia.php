<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialCobrosDia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_cobros_dia', function (Blueprint $table) {
            $table->id();
            $table->integer("id_cobrador");
            $table->integer("id_contrata");
            $table->integer("id_cliente");
            $table->string("cantidad");
            $table->date("fecha");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_cobros_dia');
    }
}
