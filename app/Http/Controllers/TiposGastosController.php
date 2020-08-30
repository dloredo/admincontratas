<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\Gastos;
use App\User;
use App\Capital;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiposGastosController extends Controller
{
    public function vista_gastos(Request $request)
    {
        $categoria = $request['buscar_categoria'];
        $categorias = Categorias::all();
        $gastos = Gastos::where('id_user' , Auth::user()->id)->paginate(6);
        $gastos_admin = Gastos::orderBy('id' , 'DESC')
                        ->categoria($categoria)
                        ->paginate(6);
        return view('gastos.gastos' , compact('categorias' , 'gastos' , 'gastos_admin'));
    }
    
    public function agregarGasto(Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $gasto = $request['cantidad'];
        if($request['categoria'] == "Contratas")
        {
            Gastos::create([
                'cantidad'    => $request['cantidad'],
                'categoria'   => "Sin categoria",
                'informacion' => $request['informacion'],
                'fecha_gasto' => Carbon::now(),
                'id_user'     => Auth::user()->id,
            ]);
        }
        else
        {
            Gastos::create([
                'cantidad'    => $request['cantidad'],
                'categoria'   => "Sin categoria",
                'informacion' => $request['informacion'],
                'fecha_gasto' => Carbon::now(),
                'id_user'     => Auth::user()->id,
            ]);
            $capital = Capital::find(1);
            $capital->capital_neto -= $gasto;
            $capital->save();
        }
        

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
