<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CobranzaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $total_clientes_asignados = Clientes::where('cobrador_id', Auth::user()->id)->count(); 
        $clientes = DB::table('clientes')
            ->select('clientes.*')
            ->where('clientes.cobrador_id' , '=' , Auth::user()->id)
            ->get();
        return view('cobranza.contratas_cobrar' , ['total_clientes_asignados' => $total_clientes_asignados] , compact('clientes'));
    }

    public function verContratasCliente($id)
    {
        $contratas = DB::table('clientes')
            ->select('clientes.*' , 'contratas.*')
            ->join('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente' )
            ->where('contratas.id_cliente' , '=' , $id)
            ->get();
        $nombre =  Clientes::where('id', $id)->select('nombres' , 'apellidos')->get(); 
        return view('cobranza.verContratasCliente' , ['nombre' => $nombre] , compact('contratas'));
    }

    public function verPagosContrata($id)
    {
        $contratas = DB::table('clientes')
            ->select('clientes.*' , 'contratas.*' , 'pagos_contratas.*')
            ->leftjoin('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente' )
            ->where('contratas.id' , '=' , $id)
            ->leftjoin('pagos_contratas' , 'contratas.id_cliente' , '=' , 'pagos_contratas.id_contrata' )
            ->where('contratas.id' , '=' , $id)
            ->get();
        return view('cobranza.verPagosContrata' ,compact('contratas'));
    }
}
