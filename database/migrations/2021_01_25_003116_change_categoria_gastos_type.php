<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoriaGastosType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gastos', function ($table) {
            $table->engine = 'InnoDB';

            $table->integer('categoria_id')->nullable();
            $table->integer('subcategoria_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropColumn('categoria_id');
            $table->dropColumn('subcategoria_id');
        });
    }
}
