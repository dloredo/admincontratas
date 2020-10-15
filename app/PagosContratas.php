<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagosContratas extends Model
{
    public $timestamps = false;
    protected $table = "pagos_contratas";
    protected $fillable = [
        'id_contrata' , 'fecha_pago' , 'cantidad_pagada' , 'adeudo' , 'adelanto','estatus','confirmacion'
    ];
}
