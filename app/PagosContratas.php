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


        $pagos = self::where("id_cobrador",$idCobrador)
                        ->groupBy("id_contrata")
                        ->get();

        foreach($pagos as $pago){
            $adeudo = self::where("id_contrata", $pago->id_contrata)
                            ->where("id","<", $pago->id)
                            ->orderBy("id", "desc")
                            ->first();

            if($adeudo->estatus != 1 && $pago->cantidad_pagada >= $adeudo->adeudo ){

                self::where("id_contrata", $adeudo->id_contrata)
                        ->where("fecha_pago","<", $adeudo->fecha_pago)
                        ->update([
                            "estatus" => 1
                        ]);
            }

            
        }

    }

    static function findByContrata($id)
    {
        return self::where("id_contrata",$id)->get();
    }
}
