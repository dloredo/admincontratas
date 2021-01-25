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
        $categoria = $request['categoria_id'];
        $subcategoria = $request['subcategoria_id'];
        $usuario_id = $request['usuario_id'];

        $users = User::allDebtCollector();

        if(auth()->user()->id_rol == 1){
            $gastos = Gastos::select("gastos.*","usuarios.nombres")
            ->join("usuarios", "usuarios.id", "gastos.id_user")
            ->orderBy('id' , 'DESC')
            ->categoria($categoria)
            ->subcategoria($subcategoria)
            ->user($usuario_id)
            ->paginate(6);
        }
        else{

            $gastos = Gastos::select("gastos.*","usuarios.nombres")
                                ->join("usuarios", "usuarios.id", "gastos.id_user")
                                ->where('id_user' , Auth::user()->id)
                                ->paginate(6);
        }

        

        return view('gastos.gastos' , compact( 'gastos' , 'users', "categoria", "subcategoria","usuario_id"));
    }

    public function create()
    {
        return view("gastos.create");
    }
    
    public function getCategories()
    {
        $categorias = Categorias::select("id", "categoria")->get();
        $subCategorias = SubCategorias::select("id", "id_categoria", "sub_categoria")->get();

        return [
            "categorias" => $categorias,
            "subcategorias" => $subCategorias
        ];
    }

    public function store(Request $request)
    {
        $rules = [
            "cantidad" => "required",
            "informacion" => "required"
        ];

        if(auth()->user()->id_rol == 1)
        {
            $rules = array_merge($rules,[
                "categoria_id" => "required",
                "subcategoria_id" => "required"
            ]);
        }

        $request->validate($rules);

        $data = array_merge($request->all(),[
            'fecha_gasto' => Carbon::now(),
            'id_user'     => auth()->user()->id,
            'categoria'   => "",
        ]);

        Gastos::create($data);

        $gasto = $data['cantidad'];

        if(auth()->user()->id_rol != 1)
        {
            $cobrador = User::findOrFail(auth()->user()->id);
            $cobrador->saldo -= $gasto;
            $cobrador->save();
        }
        else{

            $capital = Capital::find(1);

            if($data['categoria_id'] == 1)
            {
                $capital->gastos += $gasto;
            }
            else
            {
                $capital->saldo_efectivo -= $gasto;
                $capital->gastos += $gasto;
            }

            $capital->save();
        }

        return redirect()->route('vista.gastos');
    }

    public function editGasto(Gastos $gasto)
    {
        return view("gastos.edit", compact("gasto"));
    }

    public function updateGasto(Request $request,Gastos $gasto)
    {
        $request->validate([
            "categoria_id" => "required",
            "subcategoria_id" => "required",
            "cantidad" => "required",
            "informacion" => "required"
        ]);

        $oldCantidad = $gasto->cantidad;
        $oldCategoria = $gasto->categoria_id;

        $gasto->cantidad = $request->cantidad;
        $gasto->categoria_id = $request->categoria_id;
        $gasto->subcategoria_id = $request->subcategoria_id;
        $gasto->informacion = $request->informacion;
        $gasto->save();

        $capital = Capital::find(1);
        $cobrador = User::findOrFail($gasto->id_user);

        if($gasto->cantidad != $oldCantidad)
        {
            $capital->gastos -= $oldCantidad;
            $capital->gastos += $gasto->cantidad;
            if($oldCategoria != 1)
            {
                $capital->saldo_efectivo += $oldCantidad;
                $capital->saldo_efectivo -= $gasto->cantidad;
            }

            $capital->save();

            if($cobrador->id_rol != 1)
            {
                $cobrador->saldo += $oldCantidad;
                $cobrador->saldo -= $gasto->cantidad;
                $cobrador->save();
            }

        }
        else{
            if(!$oldCategoria){
    
                $capital->gastos += $gasto->cantidad;
                $capital->saldo_efectivo -= $gasto->cantidad;
                $capital->save();
    
                if($cobrador->id_rol != 1)
                {
                    $cobrador->saldo -= $gasto->cantidad;
                    $cobrador->save();
                }
            }
        }
        

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
            'cantidad' => $cantidad,
            'id_cobrador' => $id,
            'tipo' => "Cargo",
            'descripcion' => "",
            'id_cliente' => 0,
            'fecha' => Carbon::now()->format("Y-m-d")
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
            'cantidad' => $cantidad,
            'id_cobrador' => $id,
            'tipo' => "Abono",
            'descripcion' => "",
            'id_cliente' => 0,
            'fecha' => Carbon::now()->format("Y-m-d")
        ]);
        $id_cobrador->saldo -= $cantidad;
        $id_cobrador->save();
        return back();
    }
}
