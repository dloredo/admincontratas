<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialCobrador extends Model
{
    protected $table = "historial_cobradores";
    protected $fillable = [
        'monto' , 'id_cobrador' 
    ];
}
