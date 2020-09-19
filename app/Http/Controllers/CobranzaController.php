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
use App\Capital;

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
        $nombre =  Clientes::where('id', $id)->select('nombres')->get(); 
        return view('cobranza.verContratasCliente' , ['nombre' => $nombre] , compact('contratas'));
    }

    public function verPagosContrata($id)
    {
        $id_contrata = $id;
        $pagos = PagosContratas::where('id_contrata' , $id)->paginate(5);
        $fecha = Carbon::now();
        $fecha_anterior = $fecha->subDay(1);
        $pago_anterior = PagosContratas::where('fecha_pago' , $fecha_anterior->format('Y-m-d'))->where('id_contrata' , $id)->count();
        if($pago_anterior > 0)
        {
            $pago_anterior = PagosContratas::where('fecha_pago' , $fecha_anterior->format('Y-m-d'))->where('id_contrata' , $id)->get();
            $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
            $contrata = Contratas::where('id' , $id)->get();
            $validar = 1;
            return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata', 'pago_anterior'));
        }
        else
        {
            $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
            $contrata = Contratas::where('id' , $id)->get();
            $validar = 2;
            return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata'));
        }
    }

    /*public function agregarPago($id,Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $saldo_cobrador = $request['cantidad_pagada']+$request['adelanto'];
        $contrata = Contratas::findOrFail($id);
        $contrata->update([
            'control_pago' => $contrata->control_pago+=$saldo_cobrador,
        ]);
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
            'estatus'           => 1,
        ]);
        $fecha = Carbon::parse($request['fecha_pago']);
        $adelanto = $request['adelanto'];
        if($adelanto > 0)
        {
            $pagar = $request['cantidad_pagar_dia'];
            $contador = $adelanto / $pagar;
            $aux = 1;
            for( $i=0; $i<intval($contador); $i++)
            {
                PagosContratas::create([
                    'id_contrata'       => $id,
                    'fecha_pago'        => $fecha->addDays($aux),
                    'cantidad_pagada'   => $pagar,
                    'adeudo'            => 0,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);

                $aux++;
                $fecha = Carbon::parse($request['fecha_pago']); 
            }
            if($adelanto % $pagar)
            {
                $saldo = $adelanto % $pagar;
                PagosContratas::create([
                    'id_contrata'       => $id,
                    'fecha_pago'        => $fecha->addDays($aux),
                    'cantidad_pagada'   => $adelanto % $pagar,
                    'adeudo'            => $pagar - $saldo,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);
            }
        }
        $id_cobrador->update([
            'saldo' => $id_cobrador->saldo+=$saldo_cobrador,
        ]);
        return back();
    } */
    public function agregarPago($id,Request $request )
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $saldo_cobrador = $request['cantidad_pagada']+$request['adelanto'];
        $pagos_contratas = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_contratas->id_contrata);
        request()->validate([
            'cantidad_pagada'   => 'required',
            'adeudo'            => 'required',
            'adelanto'          => 'required'
        ]);
        if($request['cantidad_pagada'] >= $contrata->pagos_contrata)
        {
            $pagos_contratas->update([
                'cantidad_pagada'   => $request['cantidad_pagada'],
                'adeudo'            => 0,
                'adelanto'          => $request['adelanto'],
                'estatus'           => 1,
            ]);
        }
        if($request['adeudo'] > 0)
        {
            $pagos_contratas->update([
                'cantidad_pagada'   => $request['cantidad_pagada'],
                'adeudo'            => $request['adeudo'],
                'adelanto'          => 0,
                'estatus'           => 3,
            ]);
        }
        

        if($request['adelanto'] > 0)
        {
            $pagar = $request['cantidad_pagar_dia'];
            $contador = $request['adelanto'] / $pagar;
            //dd($contador);
            $aux = 1;
            for( $i=0; $i<intval($contador); $i++)
            {
                $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                $pagos_contratas->update([
                    'cantidad_pagada'   => $request['cantidad_pagar_dia'],
                    'adeudo'            => 0,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);
                $aux++;
            }        
            if($request['adelanto'] % $pagar)
            {
                $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                $saldo = $request['adelanto'] % $pagar;
                /*PagosContratas::create([
                    'id_contrata'       => $id,
                    'fecha_pago'        => $fecha->addDays($aux),
                    'cantidad_pagada'   => $request['adelanto'] % $pagar,
                    'adeudo'            => $pagar - $saldo,
                    'adelanto'          => 0,
                    'estatus'           => 1,
                ]);*/
                $pagos_contratas->update([
                    'cantidad_pagada'   => $request['adelanto'] % $pagar,
                    'adeudo'            => $pagar - $saldo,
                    'adelanto'          => 0,
                    'estatus'           => 3,
                ]);
            }
        }
        $contrata->update([
            'control_pago' => $contrata->control_pago+=$saldo_cobrador,
        ]);
        $id_cobrador->update([
            'saldo' => $id_cobrador->saldo+=$saldo_cobrador,
        ]);
        return back();
    }
}