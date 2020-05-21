<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use App\User;
use Carbon\Carbon;
use App\Contratas;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade as PDF;

class ClientesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    
    public function index()
    {
<<<<<<< HEAD
        $clientes = Clientes::all();
        $contratas = Contratas::all();
        return view('clientes.clientes' , compact('clientes','contratas'));
=======
        $clientes = Clientes::with("cobrador")->get();
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
>>>>>>> ce1c54f741841a2b2851f8685276ca863eba1b93
    }

    public function vista_agregarCliente()
    {
        return view('clientes.agregarNuevoCliente');
    }

    public function agregarClienteNuevo(Request $request)
    {
        request()->validate([
            'nombres' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
        ]);
        $fecha = Carbon::now();
        Clientes::create([
            'nombres'         => $request['nombres'],
            'apellidos'       => $request['apellidos'],
            'direccion'       => $request['direccion'],
            'telefono'        => $request['telefono'],
            'activo'          => true,
            'fecha_registro'  => $fecha->format('Y-m-d'),
        ]);
        return redirect()->route('vista.clientes')->with('message', 'El cliente fue agregado correctamente');
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

    public function verContratas($id)
    {
        $cliente = Clientes::where('id', $id)->firstOrFail();
        $contratas = Contratas::all();
        return view('clientes.verContratas', ['cliente' => $cliente] , compact('contratas'));
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
            'fecha_inicio'          => $request['fecha_inicio'],
            'estatus'               => 0,
            'fecha_termino'         => $request['fecha_termino'],
            'bonificacion'          => 0,    
            'control_pago'          => 0,
        ]);
        return redirect()->route('vista.clientes')->with('message', 'Se le añadio una contrata con éxito');
    }

    function cambiarEstatusCliente($id,$estatus)
    {
        $cliente = Clientes::find($id);
        $cliente->activo = ($estatus == 'Activo')? false : true ;
        
        if($cliente->save())
        {
            return redirect()->route('vista.clientes')->with('message', 'Se le desactivo con éxito');
        }
        else
        {
            return redirect()->route('vista.clientes')->with('message', 'Hubo un error al cambiar el estatus del cliente');
        }
    }
}
