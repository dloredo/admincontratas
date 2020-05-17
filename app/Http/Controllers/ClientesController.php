<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use Carbon\Carbon;
use App\Contratas;
use Illuminate\Support\Facades\Redirect;

class ClientesController extends Controller
{
    public function index()
    {
        $clientes = Clientes::all();
        return view('clientes.clientes' , compact('clientes'));
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
            'fecha_registro'  => $fecha->format('Y-m-d'),
        ]);
        return redirect()->route('vista.clientes')->with('agregar', 'El cliente fue agregado correctamente');
    }

    public function vista_agregarContrata($id)
    {
        $cliente = Clientes::where('id', $id)->firstOrFail();
        $fecha = Carbon::now();
        $fecha_aux = Carbon::now();
        $fecha_finalizacion = $fecha_aux->addDays(80);
        return view('clientes.agregarContrata', ['cliente' => $cliente , 'fecha' => $fecha , 'fecha_finalizacion' => $fecha_finalizacion]);
    }

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
            'comision_procentaje'   => round($comision_procentaje,2),
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
        return redirect()->route('vista.clientes')->with('agregar', 'Se le añadio una contrata con éxito');
    }
}
