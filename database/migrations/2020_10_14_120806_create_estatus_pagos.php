<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateEstatusPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estatus_pagos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->integer("id");
            $table->string("estatus");
        });

        DB::table('estatus_pagos')->insert([
            "id" => "0",
            "estatus" => "Sin pago"
        ]);

        DB::table('estatus_pagos')->insert([
            "id" => "1",
            "estatus" => "Pagado"
        ]);

        DB::table('estatus_pagos')->insert([
            "id" => "2",
            "estatus" => "Adelanto con adeudo"
        ]);

        DB::table('estatus_pagos')->insert([
            "id" => "3",
            "estatus" => "Pago con adeudo"
        ]);

        DB::table('estatus_pagos')->insert([
            "id" => "4",
            "estatus" => "En espera de confirmación de pago"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estatus_pagos');
    }
}
