<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = "movimientos_capital";
    protected $fillable = ["tipo_movimiento","total" , "concepto"];
}
