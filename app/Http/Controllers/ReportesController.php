<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use Carbon\Carbon;
use App\Contratas;

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
        $clientes = Clientes::all();
        $pdf = \PDF::loadView('reportes.directorios' ,  compact('clientes'));
        return $pdf->stream('Directorios.pdf');
    }
}
