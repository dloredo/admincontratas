<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    protected $table = "capital";
    protected $fillable = ["capital_acumulado","saldo_efectivo","capital_parcial","comisiones" , "gastos"];
}
