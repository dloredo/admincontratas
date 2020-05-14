<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'password' => Hash::make($data['name'].".123"),
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'activo' => true,
        ]);

        return redirect()->route('vista.usuarios')->with("saved", true)->with("message", "Usuario guardado con éxito");
    }

    function cambiarEstatus($id,$estatus)
    {
        $usuario = User::find($id);
        $usuario->activo = ($estatus == 'Activo')? false : true ;
        
        if($usuario->save())
            return back()->with("saved", true)->with("message", "Usuario editado con éxito");

            return back()->with("saved", false)->with("message", "Hubo un error al cambiar el estatus del usuario");
    }

    function eliminarUsuario($id)
    {
        $usuario = User::find($id);
        if($usuario->delete())
            return back()->with("saved", true)->with("message", "Usuario eliminado con éxito");

        return back()->with("saved", false)->with("message", "Hubo un error al eliminar el usuario");
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255','unique:usuarios'],
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'id_rol' => ['required'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required'],
        ]);
    }
    
}
