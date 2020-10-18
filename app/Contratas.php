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
        'estatus' , 'fecha_termino' , 'bonificacion' , 'control_pago','fecha_entrega','dias_pago','adeudo' , 'hora_cobro'
    ];


    function cliente()
    {
        return $this->belongsTo('App\Clientes',"id_cliente");
    }

    function scopeContratasAsignadas($query,$idCobrador)
    {
        return $query->whereHas('cliente' , function($query) use ($idCobrador){
            $query->where('cobrador_id',$idCobrador);
        })->with('cliente');
    }
}
