<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    public $timestamps = false;
    protected $table = "clientes";
    protected $fillable = [
        'nombres' , 'apellidos' , 'direccion' , 'telefono' , 'fecha_registro'
    ];
}
