<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments("id");;
            $table->string('name')->unique();
            $table->string('nombres');
            $table->string('email');
            $table->integer('id_rol')->unsigned();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('direccion');
            $table->string('telefono');
            $table->integer('saldo');
            $table->boolean('activo');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign("id_rol")->references("id")->on("roles");
        });

        DB::table("usuarios")->insert(["name" => "admin",
                                    "nombres" => "Administrador prueba",
                                    "id_rol" => 1,
                                    'password' => Hash::make("admin.123"),
                                    'direccion' => "pruebas",
                                    'telefono' => "3121234567",
                                    'email' => "admin@gmail.com",
                                    'saldo' => 0,
                                    'activo' => true]);

        DB::table("usuarios")->insert(["name" => "cobrador",
                                    "nombres" => "Cobrador prueba",
                                    "id_rol" => 2,
                                    'password' => Hash::make("cobrador.123"),
                                    'direccion' => "pruebas",
                                    'telefono' => "3121234567",
                                    'email' => "cobrador@gmail.com",
                                    'saldo' => 0,
                                    'activo' => true]);                           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
