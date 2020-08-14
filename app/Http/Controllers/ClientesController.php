<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use App\User;
use Carbon\Carbon;
use App\Contratas;
use App\Capital;
use App\PagosContratas;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

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
            'telefono' => 'numeric',
            'hora_cobro' => 'required'
        ]);
        $fecha = Carbon::now();
        Clientes::create([
            'nombres'         => $request['nombres'],
            'direccion'       => $request['direccion'],
            'telefono'        => $request['telefono'],
            'activo'          => true,
            'fecha_registro'  => $fecha->format('Y-m-d'),
            'hora_cobro'      => $request['hora_cobro'],
        ]);
        return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'El cliente fue agregado correctamente');
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

        $date = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));

        if($request->input("tipoPagos") == "Pagos diarios")
        {

        
            $date->addDays(($request->input("diasPlan") - 1));

            if($request->input("opcionesPago") != 1)
            {
                $initDate = Carbon::createFromFormat('Y-m-d', $request->input("initDate"));
                $days = $this->getDays($request->input("diasPlan"), $initDate , $request->input("daysOfWeek"));
                $data["diasRestantes"] = $days;
            }

        }
        else
            $date->addWeeks(($request->input("diasPlan") - 1));


        $data["endTime"] = $date->format("Y-m-d");

        return $data;
    }

    function getDays($days,$date, $daysOfWeek)
    {

        for($i=1;$i<=$days;$i++){
            $date->addDay(1);
            $day = $date->dayOfWeek;
            
            if(!in_array($day,$daysOfWeek))
            {
                $days--;
            }
               
        }
        
        return $days;
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
        ]);

        $daysOfWeek = null;
        if(($request->input("tipo_plan_contrata") == 'Pagos diarios' && $request->input("opcionesPago") == 2 && $request->input("daysOfWeek") == null) || 
           ($request->input("tipo_plan_contrata") == 'Pagos diarios' && $request->input("opcionesPago") == 1))
        {
            $daysOfWeek = [1,2,3,4,5,6,0];
        }   
        elseif($request->input("tipo_plan_contrata") != 'Pagos diarios')
        {
            $day = Carbon::createFromFormat('Y-m-d', $request->input("fecha_inicio"))->dayOfWeek;
            $daysOfWeek = [$day];
        }
        else
        {
            $daysOfWeek = array_map('intval', $request->input("daysOfWeek"));
        }

        $comision = $request['comision'];
        $cantidad_prestada = $request['cantidad_prestada'];
        $comision_procentaje = ($comision * 100)/$cantidad_prestada;
        //dd(round($comision_procentaje,2));
        $cantidad_pagar = $cantidad_prestada + $comision;

        Contratas::create([
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
            'bonificacion'          => 0,    
            'control_pago'          => 0,
        ]);


        $capital = Capital::find(1);
        $capital->capital_neto -= $request['cantidad_prestada'];
        $capital->capital_en_prestamo += $request['cantidad_prestada'];
        $capital->comisiones += $comision;
        $capital->save();

        return redirect()->route('vista.clientes')->with('estatus',true)->with('message', 'Se le añadio una contrata con éxito');
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
