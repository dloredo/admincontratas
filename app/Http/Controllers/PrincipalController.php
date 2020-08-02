<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\User;
use App\Clientes;
use App\Capital;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrincipalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {
        $total_contratas = Contratas::count(); 
        $total_cobradores = User::where('id_rol', 2)->count(); 
        $total_clientes = Clientes::count();
        $capital_total = Capital::all();
        $total_clientes_asignados = Clientes::where('cobrador_id', Auth::user()->id)->count(); 
        $clientes = DB::table('clientes')
            ->select('clientes.*' , 'contratas.*')
            ->join('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente' )
            ->get();
        return view("principal.principal" , ['total_contratas' => $total_contratas 
                                          , 'total_cobradores' => $total_cobradores
                                          , 'total_clientes_asignados' => $total_clientes_asignados
                                          , 'total_clientes' => $total_clientes] , compact('clientes' , 'capital_total'));
    }
}