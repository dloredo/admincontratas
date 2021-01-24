<?php

namespace App\Http\Controllers;

use App\HistorialCobrador;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CobradoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function cobradores()
    {
        return view('cobradores.cobradores');
    }
    public function historial_cobradores()
    {
        return view('cobradores.cobradores');
    }
    public function historial_cobrador()
    {
        $saldo_cobrador = User::select("saldo")
        ->where("id" , Auth::user()->id)
        ->sum("saldo");
        $cargos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Cargo")
        ->where("fecha" , Carbon::now()->format("Y-m-d"))
        ->get();
        $abonos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Abono")
        ->where("fecha" , Carbon::now()->format("Y-m-d"))
        ->get();
        return view('cobradores.historial_cobrador' ,["saldo_cobrador" => $saldo_cobrador] ,compact("cargos" , "abonos"));
    }
    public function historial_cobrador_filtro(Request $request)
    {
        $saldo_cobrador = User::select("saldo")
        ->where("id" , Auth::user()->id)
        ->sum("saldo");
        $fecha = $request['fecha'];
        $cargos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Cargo")
        ->where("fecha" , $fecha)
        ->get();
        $abonos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Abono")
        ->where("fecha" , $fecha)
        ->get();
        return view('cobradores.historial_cobrador' ,["saldo_cobrador" => $saldo_cobrador] , compact("cargos" , "abonos"));
    }
}
