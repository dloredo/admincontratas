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

        $total_contratas = Contratas::count(); 
        $total_cobradores = User::where('id_rol', 2)->count(); 
        $total_clientes = Clientes::count();
        $capital_total = Capital::all();
        $total_clientes_asignados = Clientes::where('cobrador_id', Auth::user()->id)->count(); 

        $clientes = DB::table('clientes')
            ->select('clientes.*' , 'contratas.*')
            ->join('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente' )
            ->get();

            if(\Request::is('principal')){
                $cobradores = User::where('id_rol' , 2)->get();
            }
            else{
                
                $cobradores = DB::select("select c.* from contratas c 
                                    left join pagos_contratas pc on pc.id_contrata =  c.id and pc.fecha_pago = '2020-08-14'
                                    where JSON_CONTAINS(dias_pago,CAST(weekday(CURDATE()) as CHAR(50)) ,'$')
                                    and fecha_pago is null");
            }
    
        dd($cobradores);

        $saldo_esperado = User::where('id_rol' , 2)->sum('saldo');




        //dd($saldo_esperado);
        return view("principal.principal" , ['total_contratas' => $total_contratas 
                                          , 'total_cobradores' => $total_cobradores
                                          , 'total_clientes_asignados' => $total_clientes_asignados
                                          , 'total_clientes' => $total_clientes
                                          , 'saldo_esperado' => $saldo_esperado] , compact('clientes' , 'capital_total','cobradores'));
    }

    function liquidar_cobrador(Request $request)
    {
        $id_cobrador = User::findOrFail($request->id);
        $nuevo_saldo = $request['saldo_nuevo'];

        $id_cobrador->update([
            'saldo' => $nuevo_saldo,
        ]);
        return back();
    }
}