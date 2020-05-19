<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cortes extends Model
{
    protected $table = "cortes";
    protected $fillable = ["capital_total","capital_neto","capital_en_prestamo","comisiones"];
}
