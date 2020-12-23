<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use App\FechasDesestimadas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\PagosContratas;
use App\Capital;

class ContratasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if(Auth::user()->id_rol != 1)
        {
            $contratas = Contratas::contratasAsignadas(Auth::user()->id)->get();
        }
        else
        {
            //$contratas = Contratas::with("cliente")->get();
            $contratas = Clientes::select("*")
            ->join("contratas","clientes.id" , "contratas.id_cliente")
            ->orderBy("contratas.id")
            ->get();
        }
        
        return view('contratas.contratas' , compact( 'contratas'));
    }
    public function edit($id)
    { 
        //$contrata = Contratas::where("id" , $id);
        $contrata = Clientes::select("contratas.*", "clientes.nombres")
        ->join("contratas" , "clientes.id","contratas.id_cliente")
        ->where("contratas.id" , $id)
        ->get();
        return view('contratas.editarContrata' , compact('contrata'));
    }

    public function editRenovar($id)
    { 
        //$contrata = Contratas::where("id" , $id);
        $contrata = Clientes::select("contratas.*", "clientes.nombres")
        ->join("contratas" , "clientes.id","contratas.id_cliente")
        ->where("contratas.id" , $id)
        ->get();
        return view('contratas.renovarContrata' , compact('contrata'));
    }

    function obtenerFechasPagos(Request $request)
    {
        $data = [];
        $desestimateDays = $this->getDesestimateDays(); 
        $date = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));

        if($request->input("tipoPagos") == "Pagos diarios")
        {
            $dow = [0,1,2,3,4,5,6,7];

            if($request->input("opcionesPago") != 1)
            {
                $dow = $request->input("daysOfWeek");
                // $initDate = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));
                // $days = $this->getDays($request->input("diasPlan"), $initDate , $request->input("daysOfWeek"),$desestimateDays);
                //$data["diasRestantes"] = $request->input("diasPlan");
            }

            $this->getEndDate($date, $request->input("diasPlan"),$desestimateDays, 1,$dow);
        }
        else
            $this->getEndDate($date, $request->input("diasPlan"),$desestimateDays, 2);


        $data["endTime"] = $date->format("Y-m-d");

        return $data;
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
    function obtenerDiasPagos($fechaInicio,$fechaTermino,$desestimateDays,$type,$dow)
    {
        $fechaInicio  = Carbon::createFromFormat("Y-m-d",$fechaInicio);
        $dates = [];
        
        if($fechaInicio->format("Y-m-d") > $fechaTermino) return $dates;
 
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
    public function update($id,Request $request)
    {
        $contrata = Contratas::find($id);
        $capital = Capital::find(1);
        $capital->comisiones -= $contrata->comision;
        $capital->save();
        $pagos = PagosContratas::where("id_contrata" , $id)->get();
        //dd($pagos);
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

            if(sizeof($fechasPagos) == 0 || is_null($fechasPagos))
                throw new \Exception ("Hubo un error al generar los pagos, verifique la información");


            $contrata -> update([
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
                'adeudo'                => 0,
            ]);

            if(empty($pagos))
            {
                foreach ($fechasPagos as $fecha)
                {
                    PagosContratas::create([
                        'id_contrata' => $id,
                        'fecha_pago'  => $fecha,
                        'cantidad_pagada' => 0,
                        'adeudo' => 0,
                        'adelanto' => 0,
                        'estatus' => 0,
                        'confirmacion' => 0,
                    ]);
                }   
            }
            else
            {
                $pagos->each->delete();
                foreach ($fechasPagos as $fecha)
                {
                    PagosContratas::create([
                        'id_contrata' => $id,
                        'fecha_pago'  => $fecha,
                        'cantidad_pagada' => 0,
                        'adeudo' => 0,
                        'adelanto' => 0,
                        'estatus' => 0,
                        'confirmacion' => 0,
                    ]);
                }
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
            $capital->comisiones += $contrata->comision;
            $capital->save();

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            return redirect()->route('vista.contratas')->with('estatus',false)->with('message', 'Hubo un error al guardar la contrata, verifique la información');
        }
        

        //  AGREGAR NUMERO DE PAGOS TOTALES
        return redirect()->route('vista.contratas')->with('estatus',true)->with('message', 'Se edito o renovo la contrata con éxito');
    }
    public function renovar($id,Request $request)
    {
        $contrata = Contratas::find($id);
        $pagos = PagosContratas::where("id_contrata" , $id)->get();
        //dd($pagos);
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

            if(sizeof($fechasPagos) == 0 || is_null($fechasPagos))
                throw new \Exception ("Hubo un error al generar los pagos, verifique la información");


            $contrata -> update([
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
                'adeudo'                => 0,
            ]);

            if(empty($pagos))
            {
                foreach ($fechasPagos as $fecha)
                {
                    PagosContratas::create([
                        'id_contrata' => $id,
                        'fecha_pago'  => $fecha,
                        'cantidad_pagada' => 0,
                        'adeudo' => 0,
                        'adelanto' => 0,
                        'estatus' => 0,
                        'confirmacion' => 0,
                    ]);
                }   
            }
            else
            {
                $pagos->each->delete();
                foreach ($fechasPagos as $fecha)
                {
                    PagosContratas::create([
                        'id_contrata' => $id,
                        'fecha_pago'  => $fecha,
                        'cantidad_pagada' => 0,
                        'adeudo' => 0,
                        'adelanto' => 0,
                        'estatus' => 0,
                        'confirmacion' => 0,
                    ]);
                }
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
            $capital->comisiones += $comision;
            $capital->save();

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            return redirect()->route('vista.contratas')->with('estatus',false)->with('message', 'Hubo un error al guardar la contrata, verifique la información');
        }
        
        return redirect()->route('vista.contratas')->with('estatus',true)->with('message', 'Se edito o renovo la contrata con éxito');
    }
}
