<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PagosContratas extends Model
{
    public $timestamps = false;
    protected $table = "pagos_contratas";
    protected $fillable = [
        'id_contrata' , 'fecha_pago' , 'cantidad_pagada' , 'adeudo' , 'adelanto','estatus','confirmacion'
    ];

    protected $attributes = [
        'adeudo' => 0,
        'adelanto' => 0,
    ];

    static function confirmarPagos($idCobrador)
    {
        DB::statement("update pagos_contratas pc 
                        join confirmacion_pagos cp on pc.id = cp.id_pago_contrata
                        set pc.cantidad_pagada = cp.cantidad_pagada,  
                                                pc.adeudo = cp.adeudo,
                                                pc.adelanto = cp.adelanto, 
                                                pc.estatus = cp.estatus,
                                                pc.confirmacion = 2
                        where id_cobrador = $idCobrador");

    }

    static function findByContrata($id)
    {
        return self::where("id_contrata",$id)->get();
    }
}
