<?php

namespace App\Http\Controllers;

use App\HistorialCobrador;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $cobradores = User::where('id_rol' , 2)->get();
        $saldo_cobrador = User::select("saldo")
        ->where('id_rol' , 2)
        ->sum("saldo");
        $cargos = HistorialCobrador::select("historial_saldo_cobrador.*" , "usuarios.nombres")
        ->join("usuarios" , "historial_saldo_cobrador.id_cobrador" , "usuarios.id")
        ->where("tipo" , "Cargo")
        ->where("fecha" , Carbon::now()->format("Y-m-d"))
        ->get();
        $abonos = HistorialCobrador::where("tipo" , "Abono")
        ->where("fecha" , Carbon::now()->format("Y-m-d"))
        ->get();
        return view('cobradores.historial_cobradores' ,["saldo_cobrador" => $saldo_cobrador] , compact("cargos" , "abonos" , "cobradores"));
    }

    public function historial_cobradores_filtro(Request $request)
    {
        $cobradores = User::where('id_rol' , 2)->get();
        $fecha = $request['fecha'];
        $id_cobrador = $request['id_cobrador'];
        $saldo_cobrador = User::select("saldo")
        ->where('id_rol' , 2)
        ->sum("saldo");
        if($id_cobrador == null || $fecha == null)
        {
            $cargos = HistorialCobrador::select("historial_saldo_cobrador.*" , "usuarios.nombres")
                                        ->join("usuarios" , "historial_saldo_cobrador.id_cobrador" , "usuarios.id")
                                        ->where("tipo" , "Cargo")
                                        ->where("fecha" , $fecha)
                                        ->get();
            $abonos = HistorialCobrador::select("historial_saldo_cobrador.*" , "usuarios.nombres")
                                        ->join("usuarios" , "historial_saldo_cobrador.id_cobrador" , "usuarios.id")
                                        ->where("tipo" , "Abono")
                                        ->where("fecha" , $fecha)
                                        ->get();
        }else{
            $cargos = HistorialCobrador::select("historial_saldo_cobrador.*" , "usuarios.nombres")
                                        ->join("usuarios" , "historial_saldo_cobrador.id_cobrador" , "usuarios.id")
                                        ->where("tipo" , "Cargo")
                                        ->where("fecha" , $fecha)
                                        ->where("id_cobrador" , $id_cobrador)
                                        ->get();
            $abonos = HistorialCobrador::select("historial_saldo_cobrador.*" , "usuarios.nombres")
                                        ->join("usuarios" , "historial_saldo_cobrador.id_cobrador" , "usuarios.id")
                                        ->where("tipo" , "Abono")
                                        ->where("fecha" , $fecha)
                                        ->where("id_cobrador" , $id_cobrador)
                                        ->get();
        }
        return view('cobradores.historial_cobradores' ,["saldo_cobrador" => $saldo_cobrador , "fecha" => $fecha] , compact("cargos" , "abonos" , "cobradores"));
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
        $fecha = $request['fecha'];
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
        return view('cobradores.historial_cobrador' , compact("cargos" , "abonos" , "saldo_cobrador" , "fecha"));
    }
}
