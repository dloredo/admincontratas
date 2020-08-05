<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ListadosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];
        if($fecha_inicio == null)
        {
            $contratas_no_pagadas = DB::table('contratas')
            ->join('clientes' , 'clientes.id' , '=' , 'contratas.id_cliente')
            ->where('contratas.estatus' , '=' , '0')
            ->get();
            return view('listados.listados', compact('contratas_no_pagadas'));
        }
        else
        {
            $contratas_no_pagadas = DB::table('contratas')
            ->join('clientes' , 'clientes.id' , '=' , 'contratas.id_cliente')
            ->whereBetween('contratas.fecha_entrega' , [$fecha_inicio, $fecha_fin] )
            ->where('contratas.estatus' , '=' , '0')
            ->get();
            return view('listados.listados', compact('contratas_no_pagadas'));
        }
    }
    public function Pagadas(Request $request)
    {
        $fecha_inicio = $request['fecha_inicio'];
        $fecha_fin = $request['fecha_fin'];

        if($fecha_inicio == null)
        {
            $contratas_pagadas = DB::table('contratas')
            ->join('clientes' , 'clientes.id' , '=' , 'contratas.id_cliente')
            ->where('contratas.estatus' , '=' , '1')
            ->get();
            return view('listados.listados', compact('contratas_pagadas'));
        }
        else
        {
            $contratas_pagadas = DB::table('contratas')
            ->join('clientes' , 'clientes.id' , '=' , 'contratas.id_cliente')
            ->whereBetween('contratas.fecha_entrega' , [$fecha_inicio, $fecha_fin] )
            ->where('contratas.estatus' , '=' , '1')
            ->get();
            return view('listados.listados', compact('contratas_pagadas'));
        }
    }
}
