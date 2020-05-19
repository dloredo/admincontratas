<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    protected $table = "capital";
    protected $fillable = ["capital_total","capital_neto","capital_en_prestamo","comisiones"];
}
