<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");
            $table->integer('id_user')->unsigned();
            $table->integer('cantidad');
            $table->string('categoria');
            $table->string('informacion');
            $table->date('fecha_gasto');

            $table->foreign("id_user")->references("id")->on("usuarios");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos');
    }
}
