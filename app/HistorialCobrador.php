<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialCobrador extends Model
{
    protected $table = "historial_saldo_cobrador";
    protected $fillable = [
        'cantidad' , 'id_cobrador' , 'tipo', 'confirmado'
    ];
}
