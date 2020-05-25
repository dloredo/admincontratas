<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use Illuminate\Support\Facades\DB;

class ContratasController extends Controller
{
    public function index()
    {
        $clientes = DB::table('clientes')
        ->select('clientes.id' , 'nombres','apellidos' , 'direccion' , 'telefono' , 'contratas.cantidad_prestada')
        ->join('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente')
        ->get();

        return view('contratas.contratas' , compact('clientes'));
    }
}
