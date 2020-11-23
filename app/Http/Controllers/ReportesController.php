<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use Carbon\Carbon;
use App\Contratas;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporteDirectorios()
    {
        $clientes = Clientes::all();
        $pdf = \PDF::loadView('reportes.directorios' ,  compact('clientes'));
        //$pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Directorios.pdf');
    }

    public function reporte_general_cobranza()
    {
        $cobranza = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.id as idPago' , 'pagos_contratas.cantidad_pagada' )
        ->join("clientes","clientes.id","contratas.id_cliente")
        ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
        ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
        ->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 2 )")
        ->where('pagos_contratas.confirmacion', 0 )
        ->get();
        $pdf = \PDF::loadView('reportes.reporte_general_cobranza' ,  compact('cobranza'));
        return $pdf->stream('Reporte-general-cobranza.pdf');
    }
    public function estadoCuenta($id)
    {
        $cliente = Clientes::select("contratas.id","clientes.nombres","clientes.direccion","clientes.colonia","clientes.ciudad","clientes.telefono")
        ->join("contratas" , "clientes.id" , "contratas.id_cliente")
        ->where("contratas.id" , $id)
        ->get();
        $contrata = Contratas::where("contratas.id" , $id)->get();
        $pagos = Contratas::select("contratas.cantidad_prestada","contratas.comision","contratas.cantidad_pagar","contratas.tipo_plan_contrata","contratas.dias_plan_contrata","pagos_contratas.*")
        ->join("pagos_contratas" , "contratas.id","pagos_contratas.id_contrata")
        ->where("contratas.id" , $id)
        ->where("pagos_contratas.confirmacion",2)
        ->orderBy("pagos_contratas.fecha_pago")
        ->get();
        $saldo_actual = Contratas::select("pagos_contratas.cantidad_pagada")
        ->join("pagos_contratas" , "contratas.id","pagos_contratas.id_contrata")
        ->where("contratas.id" , $id)
        ->sum("pagos_contratas.cantidad_pagada");
        //dd($saldo_actual);
        $pdf = \PDF::loadView('reportes.estado_cuenta_cliente' , ['saldo_actual' => $saldo_actual] ,compact('cliente', 'pagos','contrata'));
        return $pdf->stream('Reporte-estado-cuenta-cliente.pdf');
    }

    public function SaldoGlobalClientes()
    {
        $clientes = Clientes::select("contratas.id" , "clientes.nombres","contratas.cantidad_pagar","pagos_contratas.cantidad_pagada" )
        ->join("contratas" , "clientes.id","contratas.id_cliente")
        ->join("pagos_contratas" , "contratas.id","pagos_contratas.id_contrata")
        ->selectRaw(" sum(pagos_contratas.cantidad_pagada) as parcial ")
        ->groupBy("contratas.id")
        ->get();
        $pdf = \PDF::loadView('reportes.saldo_global_clientes' , compact('clientes'));
        return $pdf->stream('Reporte-saldo-global-clientes.pdf');
    }
}
