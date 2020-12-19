<?php

namespace App\Http\Controllers;

use App\Categorias;
use App\Gastos;
use App\User;
use App\Capital;
use App\Historial;
use App\HistorialCobrador;
use App\SubCategorias;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiposGastosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function vista_gastos(Request $request)
    {
        $categoria = $request['buscar_categoria'];
        $usuario_id = $request['usuario_id'];

        $categorias = Categorias::all();
        $users = User::allDebtCollector();

        $gastos = Gastos::select("gastos.*","usuarios.nombres")
                            ->join("usuarios", "usuarios.id", "gastos.id_user")
                            ->where('id_user' , Auth::user()->id)
                            ->paginate(6);

        $gastos_admin = Gastos::select("gastos.*","usuarios.nombres")
                        ->join("usuarios", "usuarios.id", "gastos.id_user")
                        ->orderBy('id' , 'DESC')
                        ->categoria($categoria)
                        ->user($usuario_id)
                        ->paginate(6);

        return view('gastos.gastos' , compact('categorias' , 'gastos' , 'gastos_admin','users'));
    }
    
    public function agregarGasto(Request $request)
    {
        $id_cobrador = User::findOrFail(Auth::user()->id);
        $gasto = $request['cantidad'];
        $capital = Capital::find(1);
        if($request['categoria'] == "Contratas")
        {
            Gastos::create([
                'cantidad'    => $request['cantidad'],
                'categoria'   => "Sin categoria",
                'informacion' => $request['informacion'],
                'fecha_gasto' => Carbon::now(),
                'id_user'     => Auth::user()->id,
            ]);
            $capital->gastos += $gasto;
            $capital->save();
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
            $capital->saldo_efectivo -= $gasto;
            $capital->gastos += $gasto;
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

    public function edit($id,Request $request)
    {
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

    public function VerSubcategorias($id)
    {
        $categoria = Categorias::where('id' , $id)->get();
        //dd($id);
        $sub_categorias = SubCategorias::where('id_categoria' , $id)->get();
        return view('categorias.sub_categorias' ,compact('sub_categorias' , 'categoria'));
    }

    public function agregarSubcategoria($id , Request $request)
    {
        SubCategorias::create([
            'sub_categoria' => $request['sub_categoria'],
            'id_categoria' => $id,
        ]);
        return back();  
    }
    public function editarSubcategoria($id , Request $request)
    {
        $sub_categoria = SubCategorias::find($id);
        $sub_categoria->update([
            'sub_categoria' => $request['sub_categoria'],
        ]);
        return back();  
    }

    public function eliminarSubcategoria($id)
    {
        $sub_categoria = SubCategorias::find($id);
        $sub_categoria->delete();
        return back();  
    }

    public function edit_gasto_categoria($id , Request $request)
    {
        $gasto = Gastos::findOrfail($id);
        $gasto->categoria = $request['categoria'];
        $gasto->save();
        return redirect()->route('vista.gastos');
    }

    public function Entregar($id,Request $request)
    {
        $id_cobrador = User::findOrFail($id);
        $cantidad = $request['cantidad'];
        Historial::create([
            'cantidad' => $cantidad,
            'tipo_movimiento' => 'Aportacion',
            'id_cobrador' => $id,
        ]);

        HistorialCobrador::create([
            'monto' => $cantidad,
            'id_cobrador' => $id,
        ]);

        $id_cobrador->saldo += $cantidad;
        $id_cobrador->save();
        return back();        
    }

    public function Recibi($id,Request $request)
    {
        $id_cobrador = User::findOrFail($id);
        $cantidad = $request['cantidad'];
        Historial::create([
            'cantidad' => $cantidad,
            'tipo_movimiento' => 'Retiro',
            'id_cobrador' => $id,
        ]);
        HistorialCobrador::create([
            'monto' => $cantidad,
            'id_cobrador' => $id,
        ]);
        $id_cobrador->saldo -= $cantidad;
        $id_cobrador->save();
        return back();
    }
}
