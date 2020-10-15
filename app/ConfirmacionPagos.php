<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmacionPagos extends Model
{
    public $timestamps = false;
    protected $table = "confirmacion_pagos";
    protected $fillable = [
        'id_pago_contrata','id_cobrador','id_contrata' , 'cantidad_pagada' , 'adeudo' , 'adelanto','estatus'
    ];
}
