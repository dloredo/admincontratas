<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\User;
use App\Clientes;
use App\Capital;
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

            $data["total_clientes"] = Clientes::count();

        }
        else{
            $data["total_clientes"] = Clientes::where("cobrador_id",Auth::user()->id)->count();
            $data["saldo"] = User::where("id",Auth::user()->id)->select('saldo')->get();
        }

        if(\Request::is('principal')){
            $data["infoTable"] = User::where('id_rol' , 2)->get();
        }
        else{

            if(Auth::user()->id_rol == 1){
            $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres", "pagos_contratas.adeudo")
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
                            ->where("pagos_contratas.estatus",0)
                            ->get();

            }
            else{

                $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres")
                            ->join("clientes",function($join){
                                $join->on("clientes.id","contratas.id_cliente");
                                $join->where("clientes.cobrador_id",Auth::user()->id);
                            })
                            ->join("pagos_contratas","pagos_contratas.id_contrata","contratas.id")
                            ->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d") )
                            ->where("pagos_contratas.estatus",0)
                            ->get();
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

        return view("principal.principal" ,$data);
    }
}