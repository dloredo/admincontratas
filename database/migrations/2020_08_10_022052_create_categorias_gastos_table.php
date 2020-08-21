<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_gastos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->string('categoria');
            $table->timestamps();
        });
        DB::table("categorias_gastos")->insert(["categoria" => "Contratas"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias_gastos');
    }
}
