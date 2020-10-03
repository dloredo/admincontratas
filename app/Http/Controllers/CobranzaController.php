<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\User;
use App\PagosContratas;
use App\HistorialCobrosDia;
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

    public function agregarPago($id,Request $request )
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $saldo_cobrador = $request['cantidad_pagada'];
        $pagos_contratas = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_contratas->id_contrata);
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        if($contrata->pagos_contrata+$contrata->adeudo == $request['cantidad_pagada'])
        {
            $pagos_contratas->update([
                'cantidad_pagada'   => $request['cantidad_pagada'],
                'adeudo'            => 0,
                'adelanto'          => 0,
                'estatus'           => 1,
            ]);
            $contrata->adeudo = 0;
        }
        elseif($request['cantidad_pagada'] >= $contrata->pagos_contrata+$contrata->adeudo)
        {
            if( $contrata->adeudo > 0 )
            {
                $pago = $contrata->pagos_contrata+$contrata->adeudo;
                //$pago = $request['cantidad_pagada'] - $contrata->adeudo;
                //dd($request['cantidad_pagada'] - $pago);
                $pagos_contratas->update([
                    'cantidad_pagada'   => $pago,
                    'adeudo'            => 0,
                    'adelanto'          => $request['cantidad_pagada'] - $pago,
                    'estatus'           => 1,
                ]);
                $residuo = $request['cantidad_pagada'] - $pago;
                $pagar = $request['cantidad_pagar_dia'];
                $contador = $residuo / $pagar;
                $aux = 1;
                $contrata->adeudo = 0;
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
                if($residuo % $pagar)
                {
                    $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                    $saldo = $residuo % $pagar;
                    $pagos_contratas->update([
                        'cantidad_pagada'   => $residuo % $pagar,
                        'adeudo'            => $pagar - $saldo,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);
    
                    $contrata->adeudo = $pagar - $saldo;
                }
            }
            else
            {
                $pagos_contratas->update([
                    'cantidad_pagada'   => $contrata->pagos_contrata,
                    'adeudo'            => 0,
                    'adelanto'          => $request['cantidad_pagada'] - $contrata->pagos_contrata,
                    'estatus'           => 1,
                ]);
                $residuo = $request['cantidad_pagada'] - $contrata->pagos_contrata;
                $pagar = $request['cantidad_pagar_dia'];
                $contador = $residuo / $pagar;
                $aux = 1;
                $contrata->adeudo = 0;
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
                if($residuo % $pagar)
                {
                    $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                    $saldo = $residuo % $pagar;
                    $pagos_contratas->update([
                        'cantidad_pagada'   => $residuo % $pagar,
                        'adeudo'            => $pagar - $saldo,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);
    
                    $contrata->adeudo = $pagar - $saldo;
                }
            }
        }
        else
        {
            $adeudo = $contrata->pagos_contrata+$contrata->adeudo - $request['cantidad_pagada'];
            $pagos_contratas->update([
                'cantidad_pagada'   => $request['cantidad_pagada'],
                'adeudo'            => $adeudo,
                'adelanto'          => 0,
                'estatus'           => 3,
            ]);

            $contrata->adeudo = $adeudo;
        }
        
        $contrata->control_pago = $contrata->control_pago += $saldo_cobrador;
        if($pagos_contratas->fecha_pago == $contrata->fecha_termino )
            $contrata->estatus = 1;
        
            
        $contrata->update();

        HistorialCobrosDia::create([
            'id_cobrador' => Auth::user()->id,
            'cantidad' => $request['cantidad_pagada'],
            'fecha' => Carbon::now()->format("Y-m-d")
        ]);

        $id_cobrador->update([
            'saldo' => $id_cobrador->saldo+=$saldo_cobrador,
        ]);
        return back();
    }

    function hitorialCobros($fecha = null)
    {

        //dd($fecha);

        $cobros = HistorialCobrosDia::with(["cobrador","contrata","cliente"]);

        if(Auth::user()->id_rol != 1)
        {
            $cobros->where("id_cobrador",Auth::user()->id);
        }

        if($fecha != null)
        {
            $cobros->where("fecha",$fecha);
        }

        $cobros->where("fecha",Carbon::now()->format("Y-m-d"));
        $cobros = $cobros->paginate(10);

        return view("cobranza.historialCobros",compact("cobros"));
    }
}