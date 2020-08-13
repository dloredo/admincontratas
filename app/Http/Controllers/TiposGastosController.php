<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\Gastos;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiposGastosController extends Controller
{
    public function vista_gastos()
    {
        $categorias = Categorias::all();
        $gastos = Gastos::where('id_user' , Auth::user()->id)->paginate(6);
        $gastos_admin = Gastos::all();
        return view('gastos.gastos' , compact('categorias' , 'gastos' , 'gastos_admin'));
    }
    
    public function agregarGasto(Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $gasto = $request['cantidad'];
        Gastos::create([
            'cantidad'    => $request['cantidad'],
            'categoria'   => $request['categoria'],
            'informacion' => $request['informacion'],
            'fecha_gasto' => Carbon::now(),
            'id_user'     => Auth::user()->id,
        ]);
        $id_cobrador->update([
            'saldo' => $id_cobrador->saldo-=$gasto,
        ]);
        return redirect()->route('vista.gastos');
    }

    public function index()
    {
        $categorias = Categorias::all();
        return view('categorias.categorias' , compact('categorias'));
    }

    public function AgregarCategoria(Request $request)
    {
        Categorias::create([
            'categoria' => $request['tipo_gasto'],
        ]);
        return redirect()->route('vista.categorias');
    }
    public function pre_edit($id)
    {
        $categoria = Categorias::find($id);
        return view('categorias.editarCategoria' , ['categoria' => $categoria]);
    }

    public function edit($id,Request $request)
    {
        //dd($id);
        $categoria = Categorias::findOrfail($id);
        $categoria->categoria = $request['tipo_gasto_nuevo'];

        $categoria->save();
        return redirect()->route('vista.categorias');  
    }

    public function destroy($id)
    {
        $categoria = Categorias::findOrfail($id);
        $categoria->delete();
        return redirect()->route('vista.categorias');  
    }
}
