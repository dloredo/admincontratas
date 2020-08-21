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

            $data["total_contratas"] = Contratas::count();
            $data["total_cobradores"] = User::where('id_rol', 2)->count();
            $data["capital_total"] = Capital::all();
            $data["saldo_esperado"] = User::where('id_rol' , 2)->sum('saldo');

            $data["total_clientes"] = Clientes::count();

        }
        else{
            $data["total_clientes"] = Clientes::where("cobrador_id",Auth::user()->id)->count();
        }

        if(\Request::is('principal')){
            $data["infoTable"] = User::where('id_rol' , 2)->get();
        }
        else{

            if(Auth::user()->id_rol == 1){
            $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres")
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->leftJoin("pagos_contratas",function($join){
                                $join->on("pagos_contratas.id_contrata", "contratas.id");
                                $join->where("pagos_contratas.fecha_pago",Carbon::now()->format("Y-m-d"));
                            })
                            ->whereRaw('JSON_CONTAINS(dias_pago,CAST(weekday(CURDATE()) as CHAR(50)) ,\'$\')')
                            ->whereNull("pagos_contratas.fecha_pago")
                            ->get();

            }
            else{

                $data["infoTable"] = Contratas::select("contratas.*","clientes.nombres")
                            ->join("clientes",function($join){
                                $join->on("clientes.id","contratas.id_cliente");
                                $join->where("clientes.cobrador_id",Auth::user()->id);
                            })
                            ->leftJoin("pagos_contratas",function($join){
                                $join->on("pagos_contratas.id_contrata", "contratas.id");
                                $join->where("pagos_contratas.fecha_pago",Carbon::now()->format("Y-m-d"));
                            })
                            ->whereRaw('JSON_CONTAINS(dias_pago,CAST(weekday(CURDATE()) as CHAR(50)) ,\'$\')')
                            ->whereNull("pagos_contratas.fecha_pago")
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
}