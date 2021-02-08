<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\User;
use App\ConfirmacionPagos;
use App\ConfirmacionPagoAnualidad;
use App\HistorialCobrosDia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Capital;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\PagosContratas;
use App\Exports\PagosDiariosExportBook;
use App\HistorialCobrador;

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
        // if($pago_anterior > 0)
        // {
        //     $pago_anterior = PagosContratas::where('fecha_pago' , $fecha_anterior->format('Y-m-d'))->where('id_contrata' , $id)->get();
        //     $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
        //     $pagos_contratas = PagosContratas::where('id_contrata' , $id)->where('fecha_pago' , Carbon::now()->format('Y-m-d'))->get();
        //     $contrata = Contratas::where('id' , $id)->get();
        //     $validar = 1;
        //     return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata', 'pago_anterior', 'pagos_contratas'));
        // }
        // else
        // {
        //     $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
        //     $contrata = Contratas::where('id' , $id)->get();
        //     $validar = 2;
        //     return view('cobranza.verPagos' , ['total_pagado' => $total_pagado , 'id_contrata' => $id_contrata , 'validar' => $validar] ,compact('pagos' , 'contrata'));
        // }
        $total_pagado = PagosContratas::where('id_contrata' , $id)->sum('cantidad_pagada');
        $contrata = Contratas::select("contratas.*","clientes.nombres",'pagos_contratas.id as idPago' , 'pagos_contratas.cantidad_pagada','pagos_contratas.anualidad as dia_pago_anualidad')
                            ->join("clientes","clientes.id","contratas.id_cliente")
                            ->leftjoin("pagos_contratas" , function($query){
                                $query->on("pagos_contratas.id_contrata","contratas.id");
                                $query->where('pagos_contratas.fecha_pago', Carbon::now()->format("Y-m-d"));
                                $query->whereRaw("(pagos_contratas.estatus = 0 or pagos_contratas.estatus = 3 )");
                                $query->where('pagos_contratas.confirmacion', 0 );
                            })
                            ->where('contratas.id' , $id_contrata)
                            ->first();
        //dd($contrata);
        return view('cobranza.verPagos' , compact('pagos' , 'contrata' , 'total_pagado'));
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

    public function movimientosPagos($id,$request)
    {
        

        $pagos_con = PagosContratas::findOrFail($id);
        $contrata = Contratas::findOrFail($pagos_con->id_contrata);
        $pagos_contratas = PagosContratas::where("id_contrata", $pagos_con->id_contrata)
                            ->where("estatus","!=",1)
                            ->orderBy("id", "asc")
                            ->get();

       
        $cantidad_pagada = $request['cantidad_pagada'];
        //$anualidad = boolval($request['anualidad']);
        $pagar = ($pagos_con->anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata;
        $residuo = $cantidad_pagada;

        try{
            DB::beginTransaction();

            // if($anualidad)
            // {
            //     ConfirmacionPagoAnualidad::create([
            //         "id_cobrador" => auth()->user()->id,
            //         "id_contrata" => $contrata->id,
            //         "fecha_anualidad" => $pagos_con->fecha_pago
            //     ]);

            //     $cantidad_pagada -= $contrata->comision;
            // }


            $idAux = 0;
            if($contrata->adeudo == 0)
            {

                if($cantidad_pagada < $pagar){

                    if($pagos_con->cantidad_pagada == 0){

                        $pagos_con->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_con->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $cantidad_pagada,
                            'estatus'           => 3,
                        ]);

                    }
                    else{
                        
                        if(($pagos_con->cantidad_pagada + $cantidad_pagada) <= $pagar){

                            $estatus = (($pagos_con->cantidad_pagada + $cantidad_pagada) == $pagar)? 1: 3;

                            $pagos_con->update([
                                'confirmacion'           => 1,
                            ]);
        
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_con->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $pagos_con->cantidad_pagada + $cantidad_pagada,
                                'estatus'           => $estatus,
                            ]);

                        }
                        else{

                            $pago_cantidad_pagada = $pagos_con->cantidad_pagada;

                            $pagos_con->update([
                                'confirmacion'           => 1,
                            ]);
        
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_con->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => $pagar,
                                'estatus'           => 1,
                            ]);

                            $pagos_con = PagosContratas::findOrFail($id+1);

                            $pagos_con->update([
                                'confirmacion'           => 1,
                            ]);
        
                            ConfirmacionPagos::create([
                                "id_pago_contrata"  => $pagos_con->id,
                                'id_cobrador'       => Auth::user()->id,
                                'id_contrata'       => $contrata->id,
                                'cantidad_pagada'   => ($cantidad_pagada) - ($pagar - $pago_cantidad_pagada),
                                'estatus'           => 3,
                            ]);

                        }


                    }

                }
                elseif($cantidad_pagada == $pagar){

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_con->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $cantidad_pagada,
                        'estatus'           => 1,
                    ]);
                }
                else{
                    $index = 0;
                    foreach ($pagos_contratas as $pago)
                    {
                        if($residuo < $pagar) break;
        
                        if($index == 0 && $pago->cantidad_pagada > 0){
                            $residuo += $pago->cantidad_pagada;
                        }

                        $pago->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pago->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $pagar,
                            'estatus'           => 1,
                        ]);
        
                        $idAux = $pago->id;
                        $residuo -= $pagar;
        
                        $index++;
                    }
                    $saldo = $residuo % $pagar;
                    if($residuo % $pagar)
                    {
                        $pagos_contratas = PagosContratas::findOrFail($idAux + 1);

                        $pagos_contratas->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pagos_contratas->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $saldo,
                            'estatus'           => ($saldo == $pagar)? 1 :3,
                        ]);
                        
                    }
                }

                
            }
            else if($residuo < $pagar)
            {
                $pago = $pagos_contratas[0];

                $pago_cantidad_pagada = $pago->cantidad_pagada;

                if($pago_cantidad_pagada > 0){
                    
                    if(($pago_cantidad_pagada + $residuo) <= $pagar){

                        $estatus = (($pago_cantidad_pagada + $residuo) == $pagar)? 1: 3;

                        $pago->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pago->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => ($pago_cantidad_pagada + $residuo),
                            'estatus'           => $estatus,
                        ]);

                    }
                    else{

                        $pago->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pago->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => $pagar,
                            'estatus'           => 1,
                        ]);

                        $pago = $pagos_contratas[1];

                        $pagar = ($pago->anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata;

                        $pago->update([
                            'confirmacion'           => 1,
                        ]);
    
                        ConfirmacionPagos::create([
                            "id_pago_contrata"  => $pago->id,
                            'id_cobrador'       => Auth::user()->id,
                            'id_contrata'       => $contrata->id,
                            'cantidad_pagada'   => ($residuo) - ($pagar - $pago_cantidad_pagada),
                            'estatus'           => 3,
                        ]);

                    }

                }
                else{

                    $estatus = ($residuo == $pagar)? 1: 3;

                    $pago->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pago->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $residuo,
                        'estatus'           => $estatus,
                    ]);
                }

            }
            else{
            
                $index = 0;
                foreach ($pagos_contratas as $pago)
                {
                    if($residuo < $pagar) break;

                    if($index == 0 && $pago->cantidad_pagada > 0){
                        $residuo += $pago->cantidad_pagada;
                    }

                    if($index > 0){
                        $pagar = ($pago->anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata;
                    }

                    $pago->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pago->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $pagar,
                        'estatus'           => 1,
                    ]);

                    $idAux = $pago->id;
                    $residuo -= $pagar;

                    $index++;
                }
                $saldo = $residuo % $pagar;
                if($residuo % $pagar)
                {
                    $pagos_contratas = PagosContratas::findOrFail($idAux + 1);

                    $pagar = ($pagos_contratas->anualidad)? $contrata->pago_anualidad :$contrata->pagos_contrata;

                    $pagos_contratas->update([
                        'confirmacion'           => 1,
                    ]);

                    ConfirmacionPagos::create([
                        "id_pago_contrata"  => $pagos_contratas->id,
                        'id_cobrador'       => Auth::user()->id,
                        'id_contrata'       => $contrata->id,
                        'cantidad_pagada'   => $saldo,
                        'estatus'           => ($saldo == $pagar)? 1 :3,
                    ]);
                    
                }
            }
                

            HistorialCobrosDia::create([
                'id_cobrador' => Auth::user()->id,
                'cantidad' => $cantidad_pagada,
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
            throw $e;
        }
        
    }

    public function agregarPago($id,Request $request)
    {
        request()->validate([
            'cantidad_pagada'   => 'required',
        ]);
                                    
        try
        {
            $this->movimientosPagos($id,$request->all());
        }
        catch(Exception $e)
        {
            throw $e;
            dd($e->getMessage());
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
                                    
        try
        {
            $this->movimientosPagos($pagos_contratas->id,$request->all());
        }
        catch(Exception $e)
        {
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
        $nuevo_cobro = $request->input("nuevo_cobro");
        
        request()->validate([
            'nuevo_cobro'   => 'required',
        ]);
                                    
        try
        {
            DB::beginTransaction();

            $pagos_contratas = PagosContratas::where("id_contrata",$contrata_id)->where("confirmacion",1)->first();

            PagosContratas::where("id_contrata",$contrata_id)->where("confirmacion",1)->update(["confirmacion" => 0]);
            ConfirmacionPagos::where("id_contrata",$contrata_id)->delete();
            HistorialCobrosDia::where("id_contrata",$contrata_id)->where("confirmado","!=",1)->delete();

            $this->movimientosPagos($pagos_contratas->id,[ "cantidad_pagada" => $nuevo_cobro ]);

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollBack();
            return back()->with('message', 'Hubo un error al agregar el pago.' . $e->getMessage())->with('estatus',false);
        }
        
        return back()->with('message', 'Se agrego el pago con éxito.')->with('estatus',true);
    }

    public function eliminarCobro(Request $request)
    {
        $contrata_id = $request->input("id_contrata");

        PagosContratas::where("id_contrata",$contrata_id)->where("confirmacion",1)->update(["confirmacion" => 0]);
        ConfirmacionPagos::where("id_contrata",$contrata_id)->delete();
        //ConfirmacionPagoAnualidad::where("id_contrata",$contrata_id)->delete();
        HistorialCobrosDia::where("id_contrata",$contrata_id)->where("confirmado","!=",1)->delete();
        
        return back()->with('message', 'Se elimino el cobro con éxito.')->with('estatus',true);
    }

    function confirmarPagos()
    {
        try{
            DB::beginTransaction();

            $cobros = HistorialCobrosDia::where('id_cobrador', Auth::user()->id)
                                        ->where("confirmado",0)
                                        ->get();
            //return $cobros;
            
            
            PagosContratas::confirmarPagos(Auth::user()->id);

            foreach($cobros as $cobro)
            {
                HistorialCobrador::create([
                    'cantidad' => $cobro->cantidad,
                    'id_cobrador' => $cobro->id_cobrador,
                    'tipo' => "Cargo",
                    'descripcion' => "Cobranza",
                    'id_cliente' => $cobro->id_cliente,
                    'fecha' => Carbon::now()->format("Y-m-d")
                ]);
                $contrata = Contratas::find($cobro->id_contrata);
                if($contrata->adeudo > 0)
                {
                    if($contrata->adeudo <= $cobro->cantidad){
                        $contrata->adeudo = 0;
                    }
                    else{
                        $contrata->adeudo -= $cobro->cantidad;
                    }
                }

            
                $contrata->control_pago += $cobro->cantidad;
                
                if($contrata->control_pago == $contrata->cantidad_pagar )
                    $contrata->estatus = 1;


                $contrata->update();
                
            }


            $saldo = HistorialCobrosDia::selectRaw("sum(cantidad) as total_pagado")
                                        ->where("id_cobrador",Auth::user()->id)
                                        ->get()
                                        ->first();

            $cobrador = User::findOrFail(Auth::user()->id);
            $cobrador->saldo += $saldo->total_pagado;
            $cobrador->update();

            ConfirmacionPagos::where("id_cobrador",Auth::user()->id)->delete();
            //ConfirmacionPagoAnualidad::where("id_cobrador",Auth::user()->id)->delete();
            HistorialCobrosDia::where('id_cobrador', Auth::user()->id)->update(["confirmado" => 1]);
            DB::commit();
        }
        catch(Exception $e){
            DB::rollBack();
            return $e;
            return redirect()->route('historialCobranza')->with('message', "Hubo un error al confirmar : ". $e->getMessage())->with('estatus',false);
        }

        return redirect()->route('historialCobranza')->with('message', 'Se confirmaron los pagos con éxito.')->with('estatus',true);
    }
}