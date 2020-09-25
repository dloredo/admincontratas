<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CheckUserPassword;

class UsuariosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin');
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
        $name =  str_replace(' ', '', $data['name']);
        $name =  strtolower($name);

        User::create([
            'name' => $name,
            'nombres' => $data['nombres'],
            'id_rol' => $data['id_rol'],
            'password' => Hash::make($name.".123"),
            'direccion' => $data['direccion'],
            'telefono' => $data['telefono'],
            'saldo' => 0,
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
            'id_rol' => ['required'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required','numeric'],
        ]);
    }

    function cambiarContraseña()
    {
        return view("usuarios.cambiarContraseña");
    }

    function guardarNuevaContraseña(Request $request)
    {
        $form = $request->all();
        unset($form["_token"]);


        Validator::make($form, [
            'old_password' => ['required', new CheckUserPassword],
            'new_password' => ['required','same:password_confirmation'],
            'password_confirmation' => ['required'],
        ])->validate();

        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($form["new_password"]);

        if($user->save())
            return back()->with("saved", true)->with("message", "Contraseña cambiada correctamente");

        return back()->with("saved", false)->with("message", "Hubo un error al cambiar la contraseña");
    }
    
}
