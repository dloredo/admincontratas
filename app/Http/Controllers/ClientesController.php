<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
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
            'fecha_registro' => 'required',
        ]);

        Clientes::create([
            'nombres'         => $request['nombres'],
            'apellidos'       => $request['apellidos'],
            'direccion'       => $request['direccion'],
            'telefono'        => $request['telefono'],
            'fecha_registro'  => $request['fecha_registro'],
        ]);
        return redirect()->route('vista.clientes')->with('agregar', 'El cliente fue agregado correctamente');
    }

    public function vista_agregarContrata($id)
    {
        $cliente = Clientes::where('id', $id)->firstOrFail();
        return view('clientes.agregarContrata', ['cliente' => $cliente]);
    }
}
