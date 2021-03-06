<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\User;
use App\Clientes;
use App\Capital;
use App\HistorialCobrador;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class PrincipalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {

        if(Auth::user()->id_rol == 1){

            $data["total_contratas"] = Contratas::where('estatus' , 0)->count();
            $data["total_cobradores"] = User::where('id_rol', 2)->count();
            $data["capital_total"] = Capital::all();
            $data["saldo_esperado"] = User::where('id_rol' , 2)->sum('saldo');
            $data["infoTableHistorialCobradores"] = HistorialCobrador::all();
            $data["total_clientes"] = Clientes::count();

        }
        else{
            $data["total_clientes"] = Clientes::where("cobrador_id",Auth::user()->id)->count();
            $data["saldo"] = User::where("id",Auth::user()->id)->select('saldo')->get();
        }

        if(\Request::is('principal')){
            if(Auth::user()->id_rol == 1)
                $data["infoTable"] = User::select("usuarios.*")
                ->leftjoin("control_saldos" , function($query){
                    $query->on("control_saldos.id_cobrador" , "usuarios.id");
                    //$query->selectRaw(" (sum(control_saldos.abonos)) as abonos");
                    $query->where('control_saldos.fecha' , Carbon::now()->format("Y-m-d"));
                })
                ->selectRaw(" (sum(control_saldos.abonos)) as abonos")
                ->where("id_rol" , 2)
                ->orderBy("usuarios.id")
                ->groupBy("usuarios.id")
                ->get();
            else
                // $data["infoTable"] = User::where('id_rol' , 2)->where('id' , Auth::user()->id)->get();
                $data["infoTable"] = User::select("usuarios.*")
                ->leftjoin("control_saldos" , function($query){
                    $query->on("control_saldos.id_cobrador" , "usuarios.id");
                    //$query->selectRaw(" (sum(control_saldos.abonos)) as abonos");
                    $query->where('control_saldos.fecha' , Carbon::now()->format("Y-m-d"));
                })
                ->selectRaw(" (sum(control_saldos.abonos)) as abonos")
                ->where("usuarios.id_rol" , 2)
                ->where('usuarios.id' , Auth::user()->id)
                ->orderBy("usuarios.id")
                ->groupBy("usuarios.id")
                ->get();
        }
        else{

            if(Auth::user()->id_rol == 1){
            $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.id as idPago' , 'pagos_contratas.cantidad_pagada','pagos_contratas.anualidad as dia_pago_anualidad')
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
                            ->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 3 )")
                            ->where('pagos_contratas.confirmacion', 0 )
                            ->get();


            $data["infoTableDeudores"] = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.anualidad as dia_pago_anualidad')
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('contratas.tipo_plan_contrata', 'Pagos por semana')
                            ->where("contratas.adeudo", ">", 0)
                            ->where('pagos_contratas.fecha_pago', "<",   Carbon::now()->format("Y-m-d"))
                            ->distinct()
                            ->get();

            }
            else{

                $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.id as idPago' , 'pagos_contratas.cantidad_pagada','pagos_contratas.anualidad as dia_pago_anualidad')
                            ->join("clientes",function($join){
                                $join->on("clientes.id","contratas.id_cliente");
                                $join->where("clientes.cobrador_id",Auth::user()->id);
                            })
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
                            ->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 3 )")
                            ->where('pagos_contratas.confirmacion', 0 )
                            ->get();

                $data["infoTableDeudores"] = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.anualidad as dia_pago_anualidad')
                            ->join("clientes",function($join){
                                $join->on("clientes.id","contratas.id_cliente");
                                $join->where("clientes.cobrador_id",Auth::user()->id);
                            })
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('contratas.tipo_plan_contrata', 'Pagos por semana')
                            ->where("contratas.adeudo", ">", 0)
                            ->where('pagos_contratas.fecha_pago', "<",   Carbon::now()->format("Y-m-d"))
                            ->distinct()
                            ->get();

                
                // $data["infoTable1"] = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.id as idPago' , 'pagos_contratas.cantidad_pagada')
                // ->join("clientes",function($join){
                //     $join->on("clientes.id","contratas.id_cliente");
                //     $join->where("clientes.cobrador_id",Auth::user()->id);
                // })
                // ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                // ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
                // ->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 2 )")
                // ->where('pagos_contratas.confirmacion', 0 )
                // ->get();
            }
            
        }
            
        return view("principal.principal" ,$data);
    }

    function liquidar_cobrador(Request $request)
    {
        $id_cobrador = User::findOrFail($request->id);
        $nuevo_saldo = $request['saldo_nuevo'];

        $id_cobrador->update([
            'saldo' => $id_cobrador->saldo-=$nuevo_saldo,
        ]);
        return back();
    }

    function contratasAVencer()
    {
        $data["total_contratas"] = Contratas::where('estatus' , 0)->count();
        $data["total_cobradores"] = User::where('id_rol', 2)->count();
        $data["capital_total"] = Capital::all();
        $data["saldo_esperado"] = User::where('id_rol' , 2)->sum('saldo');
        $data["total_clientes"] = Clientes::count();

        $fecha = Carbon::now();
        $fechaInicio = $fecha->format("Y-m-d");
        $fecha->addDay(10);
        $fechaFin = $fecha->format("Y-m-d");


        $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres")
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->where('contratas.fecha_inicio',">=", $fechaInicio )
                            ->orWhere('contratas.fecha_termino', "<=", $fechaFin )
                            ->where("contratas.estatus",0)
                            ->get();
        $data["infoTableHistorialCobradores"] = HistorialCobrador::all();
        return view("principal.principal" ,$data);
    }
}