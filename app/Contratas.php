<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contratas extends Model
{
    public $timestamps = false;
    protected $table = "contratas";
    protected $fillable = [
        'id_cliente' , 'cantidad_prestada' , 'comision' , 'comision_porcentaje' , 'cantidad_pagar',
        'dias_plan_contrata' , 'pagos_contrata' , 'tipo_plan_contrata' , 'fecha_inicio',
        'estatus' , 'fecha_termino' , 'bonificacion' , 'control_pago'
    ];
}
