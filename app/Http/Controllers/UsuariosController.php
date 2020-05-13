<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    function index()
    {
        $usuarios = User::with('rol')->get();

        return view("usuarios.usuarios",compact('usuarios'));
    }

    function agregarUsuario()
    {
        return view("usuarios.crearUsuario");
    }

    function create(Request $request)
    {

        $this->validator($data = $request->all())->validate();

        User::create([
            'name' => $data['name'],
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'id_rol' => $data['id_rol'],
            'email' => "prueba@mail.com",
            'password' => Hash::make("admin.123"),
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
        ]);

        return redirect()->route('vista.usuarios')->with("saved", true)->with("message", "Usuario guardado con éxito");
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'id_rol' => ['required'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required'],
        ]);
    }
    
}
