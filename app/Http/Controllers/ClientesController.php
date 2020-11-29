<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Contratas;
use App\Capital;
use App\PagosContratas;
use App\FechasDesestimadas;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Illuminate\Support\Facades\Auth;
use DB;

class ClientesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(Auth::user()->id_rol != 1)
        {
            $clientes = Clientes::with("cobrador")->where("cobrador_id", Auth::user()->id)->get();
        }
        else
        {
            $clientes = Clientes::with("cobrador")->get();
        }
        
        $usuarios = User::activo()->cobrador()->get();
        return view('clientes.clientes' , compact('clientes','usuarios'));
    }

    function asignarCobrador(Request $request)
    {
        $data = $request->all();
        $cliente = Clientes::find($data['cliente_id']);
        $cliente->cobrador_id = $data['cobrador_id'];

        if($cliente->save())
        {
            return back()->with('message', 'Se asigno el cobrador con éxito')->with('estatus',true);
        }
       
        return back()->with('message', 'Hubo un error al asignar el cobrador')->with('estatus',false);
    }

    public function vista_agregarCliente()
    {
        return view('clientes.agregarNuevoCliente');
    }

    public function agregarClienteNuevo(Request $request)
    {
        request()->validate([
            'nombres' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'telefono_2' => 'required',
            'colonia' => 'required',
            'ciudad' => 'required',
        ]);
        $fecha = Carbon::now();
        Clientes::create([
            'nombres'         => $request['nombres'],
            'direccion'       => $request['direccion'],
            'telefono'        => $request['telefono'],
            'telefono_2'        => $request['telefono_2'],
            'activo'          => true,
            'fecha_registro'  => $fecha->format('Y-m-d'),
            'colonia'      => $request['colonia'],
            'ciudad'      => $request['ciudad'],
        ]);
        return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'El cliente fue agregado correctamente');
    }
    public function updateCliente(Request $request)
    {
        $id = $request['id_cliente'];
        $cliente = Clientes::findOrFail($id);

        request()->validate([
            'nombres' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'telefono_2' => 'required',
            'colonia' => 'required',
            'ciudad' => 'required',
        ]);
        $cliente->update([
            'nombres'     => $request['nombres'],
            'direccion'   => $request['direccion'],
            'telefono'    => $request['telefono'],
            'telefono_2'  => $request['telefono_2'],
            'colonia'     => $request['colonia'],
            'ciudad'      => $request['ciudad'],
        ]);
        return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'El cliente fue editado correctamente');
    }

    public function vista_agregarContrata($id)
    {
        $cliente = Clientes::where('id', $id)->firstOrFail();
        $fecha = Carbon::now();
        $fecha_aux = Carbon::now();
        $fecha_finalizacion = $fecha_aux->addDays(80);
        $fecha_aux_semana = Carbon::now();
        $fecha_finalizacion_semana = $fecha_aux_semana->addWeeks(10);
        return view('clientes.agregarContrata', ['cliente' => $cliente , 'fecha' => $fecha , 'fecha_finalizacion' => $fecha_finalizacion, 'fecha_finalizacion_semana' => $fecha_finalizacion_semana]);
    }

    function obtenerFechasPagos(Request $request)
    {
        $data = [];
        $desestimateDays = $this->getDesestimateDays(); 
        $date = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));

        if($request->input("tipoPagos") == "Pagos diarios")
        {
            $dow = null;

            if($request->input("opcionesPago") != 1)
            {
                $dow = $request->input("daysOfWeek");
                $initDate = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));
                $days = $this->getDays($request->input("diasPlan"), $initDate , $request->input("daysOfWeek"),$desestimateDays);
                $data["diasRestantes"] = $days;
            }

            $this->getEndDate($date, $request->input("diasPlan"),$desestimateDays, 1,$dow);
        }
        else
            $this->getEndDate($date, $request->input("diasPlan"),$desestimateDays, 2);


        $data["endTime"] = $date->format("Y-m-d");

        return $data;
    }

    function getDays($days,$date, $daysOfWeek,$desestimateDays)
    {

        for($i=1;$i<=$days;$i++){
            $date->addDay(1);
            $day = $date->dayOfWeek;
            //Si la fecha en la iteración actual se encuentra dentro de las fechas no laborales, se reinicia esa iteracion de $i;
            if (in_array($date->format("Y-m-d"),$desestimateDays))
            {
                $i--;
                continue;
            }

            //Si el dia en la iteracion actual no se encuentra en los dias seleccionados, se le resta ese dia al plan de la contrata
            if(!in_array($day,$daysOfWeek))
            {
                $days--;
            }
               
        }
        
        return $days;
    }

    function getEndDate($date,$days,$desestimateDays, $type,$dow = null)
    {
        for($i=1; $i < $days ; $i++){
            
            if($type == 1)
                $date->addDay(1);
            else
                $date->addWeeks(1);

            if ((in_array($date->format("Y-m-d"),$desestimateDays)) || ((!is_null($dow) && $i == $days-1) && !in_array($date->dayOfWeek,$dow)) )
                $i--;
        }
    }

    function getDesestimateDays()
    {

        $fechas = FechasDesestimadas::orderBy("fecha_inicio","asc")->get();
        $arrayFechas = [];

        foreach($fechas as $fecha)
        {
            $period = CarbonPeriod::create($fecha->fecha_inicio, $fecha->fecha_termino);
            $arrayPeriod = $period->toArray();

            foreach($arrayPeriod as $date)
                array_push($arrayFechas, $date->format("Y-m-d"));
        }

        return $arrayFechas;

    }   

    public function verContratas($id)
    {
        $cliente = Clientes::where('id', $id)->firstOrFail();
        $contratas = Contratas::where("id_cliente",$id)->get();
        return view('clientes.verContratas' , compact('cliente','contratas'));
    }

    function detallesContrata($idCliente,$idContrata)
    {
        $cliente = Clientes::find($idCliente);
        $contrata = Contratas::find($idContrata);
        $pagos = PagosContratas::where("id_contrata", $idContrata)->get();

        return view('clientes.detallesContrata',compact('cliente','contrata',"pagos"));
    }

    //IMPRIMIR
    public function imprimirPagosDiarios($id)
    {
        $contrata = Contratas::where('id', $id)->firstOrFail();
        $clientes = Clientes::all();
        $pdf = \PDF::loadView('clientes.PDF.generarPagosDiarios' , ['contrata' => $contrata] , compact('clientes'));
        //$pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Pagos-Diarios.pdf');
    }
    public function imprimirPagosSemanales($id)
    {
        $contrata = Contratas::where('id', $id)->firstOrFail();
        $clientes = Clientes::all();
        $pdf = \PDF::loadView('clientes.PDF.generarPagosSemanales' , ['contrata' => $contrata] , compact('clientes'));
        //$pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Pagos-Semana.pdf');
    }
    //FIN

    public function agregarContrataNueva($id,Request $request)
    {
        
        request()->validate([
            'cantidad_prestada'  => 'required',
            'comision'           => 'required',
            'tipo_plan_contrata' => 'required',
            'dias_plan_contrata' => 'required',
            'pagos_contrata'     => 'required',
            'fecha_inicio'       => 'required',
            'fecha_termino'      => 'required',
            'hora_cobro'         => 'required',
        ]);


        $fechaInicio = Carbon::createFromFormat('Y-m-d', $request->input("fecha_inicio"));
        $daysOfWeek = null;
        $type = 1;
        if(($request->input("tipo_plan_contrata") == 'Pagos diarios' && $request->input("opcionesPago") == 2 && $request->input("daysOfWeek") == null) || 
           ($request->input("tipo_plan_contrata") == 'Pagos diarios' && $request->input("opcionesPago") == 1))
        {
            $daysOfWeek = [1,2,3,4,5,6,0];
        }   
        elseif($request->input("tipo_plan_contrata") != 'Pagos diarios')
        {
            $type = 2;
            $daysOfWeek = [$fechaInicio->dayOfWeek];
        }
        else
        {
            $daysOfWeek = array_map('intval', $request->input("daysOfWeek"));
        }

        $desestimateDays = $this->getDesestimateDays(); 
        $fechasPagos = $this->obtenerDiasPagos($request['fecha_inicio'],$request['fecha_termino'],$desestimateDays,$type,$daysOfWeek);
        
        $comision = $request['comision'];
        $cantidad_prestada = $request['cantidad_prestada'];
        $comision_procentaje = ($comision * 100)/$cantidad_prestada;
        $cantidad_pagar = $cantidad_prestada + $comision;

        DB::beginTransaction();
        try{
            $contrata = Contratas::create([
                'id_cliente'            => $id,
                'cantidad_prestada'     => $request['cantidad_prestada'],
                'comision'              => $comision,
                'comision_porcentaje'   => round($comision_procentaje,2),
                'cantidad_pagar'        => $cantidad_pagar,
                'dias_plan_contrata'    => $request['dias_plan_contrata'],
                'pagos_contrata'        => $request['pagos_contrata'],
                'tipo_plan_contrata'    => $request['tipo_plan_contrata'],
                "dias_pago"             => json_encode($daysOfWeek),
                'fecha_inicio'          => $request['fecha_inicio'],
                'fecha_entrega'         => $request['fecha_entrega'],
                'estatus'               => 0,
                'fecha_termino'         => $request['fecha_termino'],
                'hora_cobro'            => $request['hora_cobro'],
                'bonificacion'          => 0,    
                'control_pago'          => 0,
                'adeudo'                => 0
            ]);


            foreach ($fechasPagos as $fecha)
            {
                PagosContratas::create([
                    'id_contrata' => $contrata->id,
                    'fecha_pago'  => $fecha,
                    'cantidad_pagada' => 0,
                    'adeudo' => 0,
                    'adelanto' => 0,
                    'estatus' => 0,
                    'confirmacion' => 0,
                ]);
            }
            
            $fechaActual = Carbon::now()->format("Y-m-d");
            if($fechaActual > $fechaInicio->format("Y-m-d"))
            {
                $fechasPagos = PagosContratas::where("id_contrata",$contrata->id)->where("fecha_pago","<", $fechaActual)->get();

                $adeudo = 0;
                foreach ($fechasPagos as $fecha)
                {
                    $adeudo += $contrata->pagos_contrata;
                    $fecha->adeudo = $adeudo;
                    $fecha->estatus = 3;
                    $fecha->update();
                }
                $contrata->adeudo = $adeudo;
                $contrata->update();
            }

            $capital = Capital::find(1);
            $capital->saldo_efectivo -= $request['cantidad_prestada'];
            $capital->capital_parcial += $request['cantidad_prestada'];
            $capital->comisiones += $comision;
            $capital->save();

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            return redirect()->route('vista.clientes')->with('estatus',false)->with('message', 'Hubo un error al guardar la contrata');
        }
        

        //  AGREGAR NUMERO DE PAGOS TOTALES
        return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'Se le añadio una contrata con éxito');
    }

    function obtenerDiasPagos($fechaInicio,$fechaTermino,$desestimateDays,$type,$dow)
    {
        $fechaInicio  = Carbon::createFromFormat("Y-m-d",$fechaInicio);

        ($fechaInicio->format("Y-m-d") != $fechaTermino);

        $dates = [];
        while(strtotime($fechaInicio->format("Y-m-d")) <= strtotime($fechaTermino) )
        {
            if (!in_array($fechaInicio->format("Y-m-d"),$desestimateDays) && in_array($fechaInicio->dayOfWeek,$dow) ) 
                array_push($dates, $fechaInicio->format("Y-m-d"));

            if($type == 1)
                $fechaInicio->addDay(1);
            else
                $fechaInicio->addWeeks(1);

            
        }

        return $dates;
    }

    function cambiarEstatusCliente($id,$estatus)
    {
        $cliente = Clientes::find($id);
        $cliente->activo = ($estatus == 'Activo')? false : true ;
        
        if($cliente->save())
        {
            return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'Se le desactivo con éxito');
        }
        else
        {
            return redirect()->route('vista.clientes')->with('estatus',false)->with('message', 'Hubo un error al cambiar el estatus del cliente');
        }
    }
}
