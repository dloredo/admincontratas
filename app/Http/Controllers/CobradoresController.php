<?php

namespace App\Http\Controllers;

use App\HistorialCobrador;
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
        $cargos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Cargo")
        ->get();
        $abonos = HistorialCobrador::where("id_cobrador" , Auth::user()->id)
        ->where("tipo" , "Abono")
        ->get();
        return view('cobradores.historial_cobrador' , compact("cargos" , "abonos"));
    }
}
