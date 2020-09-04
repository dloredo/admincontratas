<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FechasDesestimadas extends Model
{
    public $timestamps = false;
    protected $table = 'fechas_desestimadas';
    protected $fillable = ["anio","fecha_inicio" ,"fecha_termino","descripcion"];
}
