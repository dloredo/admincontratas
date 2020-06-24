<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\User;
use App\PagosContratas;
use Carbon\Carbon;
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
        /*$contratas = DB::table('clientes')
            ->select('clientes.*' , 'contratas.*' , 'pagos_contratas.*')
            ->leftjoin('contratas' , 'clientes.id' , '=' , 'contratas.id_cliente' )
            ->where('contratas.id' , '=' , $id)
            ->leftjoin('pagos_contratas' , 'contratas.id_cliente' , '=' , 'pagos_contratas.id_contrata' )
            ->where('contratas.id' , '=' , $id)
            ->get();
        $pagos = PagosContratas::where('id_contrata' , $id)->get();
        $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
        $id_contrata = $id;
        return view('cobranza.verPagosContrata' , ['id_contrata' => $id_contrata , 'total_pagado' => $total_pagado] ,compact('contratas' , 'pagos'));*/
        $id_contrata = $id;
        $pagos = PagosContratas::where('id_contrata' , $id)->paginate(6);
        $fecha = Carbon::now();
        $fecha_anterior = $fecha->subDay(1);
        $pago_anterior = PagosContratas::where('fecha_pago' , $fecha_anterior->format('Y-m-d'))->get();
        $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
        $contrata = Contratas::where('id' , $id)->get();
        //dd($contrata);
        return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata] ,compact('pagos' , 'pago_anterior' , 'contrata'));
    }

    public function agregarPago($id,Request $request)
    {
        request()->validate([
            'fecha_pago'        => 'required',
            'cantidad_pagada'   => 'required',
            'adeudo'            => 'required',
            'adelanto'          => 'required'
        ]);
        PagosContratas::create([
            'id_contrata'       => $id,
            'fecha_pago'        => $request['fecha_pago'],
            'cantidad_pagada'   => $request['cantidad_pagada'],
            'adeudo'            => $request['adeudo'],
            'adelanto'          => $request['adelanto'],
        ]);
        $fecha = Carbon::now();
        $adelanto = $request['adelanto'];
        if($adelanto > 0)
        {
            $pagar = $request['cantidad_pagar_dia'];
            $contador = $adelanto / $pagar;
            $aux = 1;
            for( $i=0; $i<$contador; $i++)
            {
                if($pagar == $pagar)
                {
                    PagosContratas::create([
                        'id_contrata'       => $id,
                        'fecha_pago'        => $fecha->addDays($aux),
                        'cantidad_pagada'   => 300,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                    ]);
                }
                $adelanto = $adelanto - $pagar;
                $aux++;
                $fecha = Carbon::now();
            } 
        }
           
        
        return back();
    }
}
