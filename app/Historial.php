<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = "historial";
    protected $fillable = [
        'cantidad' , 'tipo_movimiento' , 'id_cobrador'
    ];

}
