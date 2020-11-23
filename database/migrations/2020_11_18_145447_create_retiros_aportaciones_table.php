<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirosAportacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retiros_aportaciones', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->integer('aportaciones');
            $table->integer('retiros');
            $table->integer('comisiones');
            $table->integer('gastos');
            $table->integer('capital_acumulado');
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
        Schema::dropIfExists('retiros_aportaciones');
    }
}
