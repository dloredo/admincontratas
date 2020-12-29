<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\User;
use App\ConfirmacionPagos;
use App\HistorialCobrosDia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Capital;
use Exception;
use PagosContrata;
use Maatwebsite\Excel\Facades\Excel;
use App\PagosContratas;
use App\Exports\PagosDiariosExportBook; 

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
            $pagos_contratas = PagosContratas::where('id_contrata' , $id)->where('fecha_pago' , Carbon::now()->format('Y-m-d'))->get();
            $contrata = Contratas::where('id' , $id)->get();
            $validar = 1;
            return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata', 'pago_anterior', 'pagos_contratas'));
        }
        else
        {
            $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
            $contrata = Contratas::where('id' , $id)->get();
            $validar = 2;
            return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata'));
        }
    }

    function descargarTarjetaContrata($id)
    {
        $contrata = Contratas::with("cliente")->find($id);
        $fechas = PagosContratas::findByContrata($id);
        $fechas->toArray();

        if($contrata->tipo_plan_contrata == "Pagos diarios")
        {
            $chunks_fechas = array_chunk($fechas->toArray(),80);
            $pdf = \PDF::loadView('cobranza.TarjetaDiaria', compact("contrata","chunks_fechas"))->setPaper('a4', 'landscape');
        }
        else
        {
            $chunks_fechas = array_chunk($fechas->toArray(),10);
            $pdf = \PDF::loadView('cobranza.TarjetaSemanal', compact("contrata","chunks_fechas"))->setPaper('a4', 'landscape');
        }
        
        
        return $pdf->stream();

        //return view("cobranza.TarjetaDiaria", compact("contrata","chunks_fechas"));
        //return Excel::download(new PagosDiariosExportBook($chunks_fechas), 'Tarjeton'.$contrata->tipo_plan_contrata.'.xlsx');
        
    }

    public function agregarPagoPrototipo($id , Request $request)
    {
        $pagos_con = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_con->id_contrata);
        $pagos_contratas = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                            //->where("id","<=", $id)
                            ->get();
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        $cantidad_pagada = $request['cantidad_pagada'];
        $pagar = $contrata->pagos_contrata;
        $residuo = $cantidad_pagada;

        $idAux = 0;
        if( $cantidad_pagada == $pagar )
        {
            $pagos_con->update([
                'cantidad_pagada'   => $cantidad_pagada,
                'adeudo'            => $pagar-$cantidad_pagada,
                'adelanto'          => 0,
                'estatus'           => 1,
            ]);
            $contrata->adeudo = 0;
            $contrata->save();
        }
        else if( $cantidad_pagada < $pagar + $contrata->adeudo )
        {
            $pagos_con->update([
                'cantidad_pagada'   => $cantidad_pagada,
                'adeudo'            => $pagar-$cantidad_pagada,
                'adelanto'          => 0,
                'estatus'           => 3,
            ]);
            $contrata->adeudo += $pagar-$cantidad_pagada;
            $contrata->save();
        }
        else if( $cantidad_pagada > $pagar + $contrata->adeudo )
        {
            foreach ($pagos_contratas as $pago)
            {
                if($pago->estatus != 1)
                {
                    $pago->update([
                        'cantidad_pagada'   => $pagar,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    $idAux = $pago->id;
                    $residuo -= $pagar;

                    if($residuo < $pagar) break;
                }
            }
            $saldo = $residuo % $pagar;
            if($residuo % $pagar)
            {
                $pagos_contratas = PagosContratas::findOrFail($idAux + 1);
                

                $pagos_contratas->update([
                    'cantidad_pagada'   => $saldo,
                    'adeudo'            => $contrata->pagos_contrata - $saldo,
                    'adelanto'          => 0,
                    'estatus'           => 3,
                ]);
                if( $cantidad_pagada >= $contrata->adeudo )
                {
                    $contrata->adeudo = $contrata->pagos_contrata - $saldo;
                    $contrata->save();
                }
                else
                {
                    $contrata->adeudo += $contrata->pagos_contrata - $saldo;
                    $contrata->save();
                }
            }
        }
        
        

        return back()->with('message', 'Se agrego el cobro con éxito.')->with('estatus',true);
        
    }

    public function agregarPago($id,Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $saldo_cobrador = $request['cantidad_pagada'];
        $pagos_contratas = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_contratas->id_contrata);
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        //dd($pagos_contratas);
        try{
            DB::beginTransaction();


            if( $pagos_contratas->estatus == 2 || $pagos_contratas->estatus == 3 )
            {
                $pago_anterior = $pagos_contratas->cantidad_pagada;
                if( $request['cantidad_pagada'] + $pago_anterior > $contrata->pagos_contrata  )
                {
                    $pago = $request['cantidad_pagada'] + $pago_anterior;
                    $residuo = $request['cantidad_pagada']+$pago_anterior - $contrata->pagos_contrata;
                    $pagar = $contrata->pagos_contrata;
                    $contador = $residuo / $contrata->pagos_contrata;
                    $aux = 1;

                    $pagos_contratas->update([
                        'confirmacion'           =>1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata" => $pagos_contratas->id,
                        'id_cobrador' => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $contrata->pagos_contrata,
                        'adeudo'            => 0,
                        'adelanto'          => $residuo,
                        'estatus'           => 1,
                    ]);

                    for( $i=0; $i<intval($contador); $i++)
                    {
                    
                        $pagos_contratas = PagosContratas::findOrFail($id+$aux);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
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

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $residuo % $pagar,
                            'adeudo'            => $pagar - $saldo,
                            'adelanto'          => 0,
                            'estatus'           => 2,
                        ]);
        
                        //$contrata->adeudo = $pagar - $saldo;
                    }
                }
                else if( $request['cantidad_pagada'] + $pago_anterior == $contrata->pagos_contrata )
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]); 

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    //$contrata->adeudo = 0;
                }
                else if( $request['cantidad_pagada'] + $pago_anterior < $contrata->pagos_contrata )
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => $contrata->pagos_contrata - $request['cantidad_pagada'] - $pago_anterior,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                    //$contrata->adeudo += $contrata->pagos_contrata - $request['cantidad_pagada'] - $pago_anterior; 
                } 
                else
                {
                    dd("No hay");
                }
            }
            else
            {
                if($contrata->pagos_contrata+$contrata->adeudo == $request['cantidad_pagada'])
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'],
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                    //$contrata->adeudo = 0;
                }
                elseif($request['cantidad_pagada'] >= $contrata->pagos_contrata+$contrata->adeudo)
                {
                    if( $contrata->adeudo > 0 )
                    {
                        $pago = $contrata->pagos_contrata+$contrata->adeudo;
                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $pago,
                            'adeudo'            => 0,
                            'adelanto'          => $request['cantidad_pagada'] - $pago,
                            'estatus'           => 1,
                        ]);

                        $residuo = $request['cantidad_pagada'] - $pago;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                        //$contrata->adeudo = 0;
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $contrata->pagos_contrata,
                            //     'adeudo'            => 0,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 1,
                            // ]);
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
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
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $residuo % $pagar,
                            //     'adeudo'            => $pagar - $saldo,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 3,
                            // ]);
            
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 3,
                            ]);
                            //$contrata->adeudo = $pagar - $saldo;
                        }
                    }
                    else
                    {
                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
                            'adeudo'            => 0,
                            'adelanto'          => $request['cantidad_pagada'] - $contrata->pagos_contrata,
                            'estatus'           => 1,
                        ]);

                        $residuo = $request['cantidad_pagada'] - $contrata->pagos_contrata;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                        //$contrata->adeudo = 0;
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $contrata->pagos_contrata,
                            //     'adeudo'            => 0,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 1,
                            // ]);

                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
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
                            // $pagos_contratas->update([
                            //     'cantidad_pagada'   => $residuo % $pagar,
                            //     'adeudo'            => $pagar - $saldo,
                            //     'adelanto'          => 0,
                            //     'estatus'           => 2,
                            // ]);
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 2,
                            ]);
                            //$contrata->adeudo = $pagar - $saldo;
                        }
                    }
                }
                else
                {
                    $adeudo = $contrata->pagos_contrata+$contrata->adeudo - $request['cantidad_pagada'];
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'],
                        'adeudo'            => $adeudo,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                    //$contrata->adeudo = $adeudo;
                }
            }
                
            
            // $contrata->control_pago = $contrata->control_pago += $saldo_cobrador;
            // if($pagos_contratas->fecha_pago == $contrata->fecha_termino )
            //     $contrata->estatus = 1;

            HistorialCobrosDia::create([
                'id_cobrador' => Auth::user()->id,
                'cantidad' => $request['cantidad_pagada'],
                'id_contrata' => $contrata->id,
                'id_cliente' => $contrata->id_cliente,
                'confirmado' => 0,
                'fecha' => Carbon::now()->format("Y-m-d")
            ]);
            //$contrata->update();
            // $id_cobrador->update([
            //     'saldo' => $id_cobrador->saldo+=$saldo_cobrador,
            // ]);

            DB::commit();
        }
        catch(Exception $e)
        {
            
            DB::rollBack();

            return back()->with('message', 'Hubo un error al agregar el pago.')->with('estatus',false);
        }

        return back()->with('message', 'Se agrego el pago con éxito.')->with('estatus',true);
    }

    public function agregarPagoAdeudo(Contratas $contrata,Request $request)
    {
        $pagos_contratas = PagosContratas::where("id_contrata",$contrata->id)
                                            ->where("fecha_pago","<",Carbon::now()->format("Y-m-d"))
                                            ->orderBy("fecha_pago","desc")
                                            ->first();


        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
        

        try{
            DB::beginTransaction();


            if( $pagos_contratas->estatus == 2 || $pagos_contratas->estatus == 3 )
            {
                $pago_anterior = $pagos_contratas->cantidad_pagada;
                $adeudo_anterior =  $pagos_contratas->adeudo;

                if( $request['cantidad_pagada'] > $pagos_contratas->adeudo  )
                {
                    $residuo = $request['cantidad_pagada'] - $pagos_contratas->adeudo;
                    $pagar = $contrata->pagos_contrata;

                    $contador = $residuo / $contrata->pagos_contrata;
                    $aux = 1;

                    $pagos_contratas->update([
                        'confirmacion'      =>1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $pago_anterior + $adeudo_anterior,
                        'adeudo'            => 0,
                        'adelanto'          => $residuo,
                        'estatus'           => 1,
                        'pago_atrasado'     => true,
                        'cantidad_pago_atrasado' => $adeudo_anterior
                    ]);

                    $id = $pagos_contratas->id;
                    for( $i=0; $i<intval($contador); $i++)
                    {
                    
                        $pagos_contratas = PagosContratas::findOrFail($id+$aux);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
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

                        $estatus = ($pagos_contratas->fecha_pago == Carbon::now()->format("Y-m-d"))? 3:2;

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $residuo % $pagar,
                            'adeudo'            => $pagar - $saldo,
                            'adelanto'          => 0,
                            'estatus'           => $estatus,
                        ]);
        
                    }
                }
                else if( $request['cantidad_pagada'] == $pagos_contratas->adeudo )
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]); 

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                        'pago_atrasado'     => true,
                        'cantidad_pago_atrasado' => $request['cantidad_pagada']
                    ]);

                }
                else
                {
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $request['cantidad_pagada'] + $pago_anterior,
                        'adeudo'            => $adeudo_anterior - $request['cantidad_pagada'],
                        'adelanto'          => 0,
                        'estatus'           => 3,
                        'pago_atrasado'     => true,
                        'cantidad_pago_atrasado' => $request['cantidad_pagada']
                    ]);
                }
            }
                
            HistorialCobrosDia::create([
                'id_cobrador' => Auth::user()->id,
                'cantidad' => $request['cantidad_pagada'],
                'id_contrata' => $contrata->id,
                'id_cliente' => $contrata->id_cliente,
                'confirmado' => 0,
                'fecha' => Carbon::now()->format("Y-m-d")
            ]);

            DB::commit();
        }
        catch(Exception $e)
        {
            
            DB::rollBack();

            return back()->with('message', 'Hubo un error al agregar el pago.')->with('estatus',false);
        }

        return back()->with('message', 'Se agrego el pago con éxito.')->with('estatus',true);
    }

    function hitorialCobros($fecha = null , $cobrador = null)
    {
        $cobradores = User::where("id_rol" , 2)->get();
        $cobros = HistorialCobrosDia::with(["cobrador","contrata","cliente"]);
        $cobroTotal = HistorialCobrosDia::select();
        $confirmar = HistorialCobrosDia::select();
        if(Auth::user()->id_rol != 1)
        {
            $cobros->where("id_cobrador",Auth::user()->id);
            $cobroTotal->where("id_cobrador",Auth::user()->id);
            $confirmar->where("id_cobrador",Auth::user()->id);
        }

        if($fecha != null && $cobrador != null)
        {
            $cobros->where("fecha",$fecha)->where("id_cobrador" , $cobrador);
            $cobroTotal->where("fecha",$fecha)->where("id_cobrador" , $cobrador);
            $confirmar->where("fecha",$fecha)->where("id_cobrador" , $cobrador);
        }
        if($fecha != null)
        {
            $cobros->where("fecha",$fecha);
            $cobroTotal->where("fecha",$fecha);
            $confirmar->where("fecha",$fecha);
        }
        if($cobrador != null)
        {
            $cobros->where("id_cobrador",$cobrador);
            $cobroTotal->where("id_cobrador",$cobrador);
            $confirmar->where("id_cobrador",$cobrador);
        }
        else
        {
            $cobros->where("fecha",Carbon::now()->format("Y-m-d"));
            $cobroTotal->where("fecha",Carbon::now()->format("Y-m-d"));
            $confirmar->where("fecha",Carbon::now()->format("Y-m-d"));
        }

        $confirmar = $confirmar->where("confirmado",0)->get()->count();
        $cobros = $cobros->paginate(10);
        $cobroTotal = $cobroTotal->get()->sum("cantidad");
       

        return view("cobranza.historialCobros",compact("cobros","cobroTotal","confirmar", "cobradores"));
    }

    function editarCobro(Request $request)
    {
        /*
            Confirmacion: 0 no se ha confirmado
                          1 en espera de confirmacion
                          2 confirmado
        */

        $contrata_id = $request->input("contrata_id");
        $cobro_id = $request->input("cobro_id");
        $nuevo_cobro = $request->input("nuevo_cobro");
        
        request()->validate([
            'nuevo_cobro'   => 'required',
        ]);

        try{

            DB::beginTransaction();

            $pagos_contratas = PagosContratas::where("id_contrata",$contrata_id)->where("confirmacion",1)->first();
            $id = $pagos_contratas->id;
            $contrata = Contratas::findOrFail($contrata_id);
            ConfirmacionPagos::where("id_contrata",$contrata_id)->delete();

            if( $pagos_contratas->estatus == 2 )
            {
                $pago_anterior = $pagos_contratas->cantidad_pagada;

                if( $nuevo_cobro + $pago_anterior > $contrata->pagos_contrata  )
                {
                    $pago = $nuevo_cobro + $pago_anterior;
                    $residuo = $nuevo_cobro+$pago_anterior - $contrata->pagos_contrata;
                    $pagar = $contrata->pagos_contrata;
                    $contador = $residuo / $contrata->pagos_contrata;
                    $aux = 1;

                    ConfirmacionPagos::create([
                        "id_pago_contrata" => $pagos_contratas->id,
                        'id_cobrador' => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $contrata->pagos_contrata,
                        'adeudo'            => 0,
                        'adelanto'          => $residuo,
                        'estatus'           => 1,
                    ]);

                    for( $i=0; $i<intval($contador); $i++)
                    {
                    
                        $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
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
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $residuo % $pagar,
                            'adeudo'            => $pagar - $saldo,
                            'adelanto'          => 0,
                            'estatus'           => 2,
                        ]);

                    }
                }
                
                if( $nuevo_cobro + $pago_anterior == $contrata->pagos_contrata )
                {
                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $nuevo_cobro + $pago_anterior,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);
                }
            
                if( $nuevo_cobro + $pago_anterior < $contrata->pagos_contrata )
                {

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $nuevo_cobro + $pago_anterior,
                        'adeudo'            => $contrata->pagos_contrata - $nuevo_cobro - $pago_anterior,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                } 

            }
            else
            {
                if($contrata->pagos_contrata+$contrata->adeudo == $nuevo_cobro)
                {
                
                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $nuevo_cobro,
                        'adeudo'            => 0,
                        'adelanto'          => 0,
                        'estatus'           => 1,
                    ]);

                }
                elseif($nuevo_cobro >= $contrata->pagos_contrata+$contrata->adeudo)
                {
                    if( $contrata->adeudo > 0 )
                    {
                        $pago = $contrata->pagos_contrata+$contrata->adeudo;

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $pago,
                            'adeudo'            => 0,
                            'adelanto'          => $nuevo_cobro - $pago,
                            'estatus'           => 1,
                        ]);

                        $residuo = $nuevo_cobro - $pago;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                    
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
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
                            
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 3,
                            ]);
                        
                        }
                    }
                    else
                    {
                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);

                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $contrata->pagos_contrata,
                            'adeudo'            => 0,
                            'adelanto'          => $nuevo_cobro - $contrata->pagos_contrata,
                            'estatus'           => 1,
                        ]);

                        $residuo = $nuevo_cobro - $contrata->pagos_contrata;
                        $pagar = $contrata->pagos_contrata;
                        $contador = $residuo / $pagar;
                        $aux = 1;
                    
                        for( $i=0; $i<intval($contador); $i++)
                        {
                            $pagos_contratas = PagosContratas::findOrFail($id+$aux);

                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $contrata->pagos_contrata,
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
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_contratas->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $residuo % $pagar,
                                'adeudo'            => $pagar - $saldo,
                                'adelanto'          => 0,
                                'estatus'           => 2,
                            ]);
                        }
                    }
                }
                else
                {
                    $adeudo = $contrata->pagos_contrata+$contrata->adeudo - $nuevo_cobro;
                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $nuevo_cobro,
                        'adeudo'            => $adeudo,
                        'adelanto'          => 0,
                        'estatus'           => 3,
                    ]);

                }
            }


            HistorialCobrosDia::where("id", $cobro_id)->update(["cantidad" => $nuevo_cobro]);

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollBack();

            return back()->with('message', 'Hubo un error al editar: '. $e->getMessage())->with('estatus',false);
        }

        return back()->with('message', 'Se edito el cobro con éxito.')->with('estatus',true);
    }

    public function eliminarCobro(Request $request)
    {
        $contrata_id = $request->input("id_contrata");
        $id = ConfirmacionPagos::where("id_contrata",$contrata_id)->get();
        foreach ($id as $id)
        {
            $pagos_contratas = PagosContratas::findOrFail($id->id_pago_contrata);
        }
        $pagos_contratas->update([
            'confirmacion'           => 0,
        ]);
        ConfirmacionPagos::where("id_contrata",$contrata_id)->delete();
        HistorialCobrosDia::where("id_contrata",$contrata_id)->delete();
        
        return back()->with('message', 'Se elimino el cobro con éxito.')->with('estatus',true);
    }

    function confirmarPagos()
    {
        try{
            DB::beginTransaction();

            HistorialCobrosDia::where('id_cobrador', Auth::user()->id)->update(["confirmado" => 1]);
            
            PagosContratas::confirmarPagos(Auth::user()->id);
    
            $pagos = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as total_pagado, sum(adeudo) as total_adeudo, id_contrata")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",false)
                                        ->groupBy("id_contrata")
                                        ->get();
            
            foreach($pagos as $pago)
            {
                $contrata = Contratas::findOrFail($pago->id_contrata);
                $contrata->adeudo = $pago->total_adeudo;
                $contrata->control_pago += $pago->total_pagado;
                $contrata->update();
            }

            $pagosAtrasados = ConfirmacionPagos::selectRaw("sum(cantidad_pago_atrasado) as total_pagado, id_contrata")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",true)
                                        ->groupBy("id_contrata")
                                        ->get();
            
            foreach($pagosAtrasados as $pago)
            {
                $contrata = Contratas::findOrFail($pago->id_contrata);
                $contrata->control_pago += $pago->total_pagado;
                $contrata->update();
            }

            

            $saldo = ConfirmacionPagos::selectRaw("sum(cantidad_pagada) as total_pagado")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",false)
                                        ->get()
                                        ->first();

            $saldoAdeudo = ConfirmacionPagos::selectRaw("sum(cantidad_pago_atrasado) as total_pagado")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->where("pago_atrasado",true)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail(Auth::user()->id);
            $cobrador->saldo += ($saldo->total_pagado + $saldoAdeudo->total_pagado);
            $cobrador->update();

            ConfirmacionPagos::where("id_cobrador",Auth::user()->id)->delete();

            DB::commit();
        }
        catch(Exception $e){

            DB::rollBack();
            return redirect()->route('historialCobranza')->with('message', "Hubo un error al confirmar : ". $e->getMessage())->with('estatus',false);
        }

        return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}