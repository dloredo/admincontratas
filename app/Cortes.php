<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cortes extends Model
{
    protected $table = "cortes";
    protected $fillable = ["capital_acumulado","saldo_efectivo","capital_parcial","comisiones",
                           "clientes","contratas","prestamos_totales","gastos","capital_total"];
}
