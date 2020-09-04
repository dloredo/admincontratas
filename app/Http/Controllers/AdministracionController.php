<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FechasDesestimadas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdministracionController extends Controller
{
    function desestimarFechas()
    {
        $fechas = FechasDesestimadas::where("anio", Carbon::now()->year)->orderBy("fecha_inicio","asc")->get();
        $años = FechasDesestimadas::select("anio")->orderBy("anio", "desc")->get();
       
        return view("desestimar_fechas.desestimarFechas",compact("fechas","años"));
    }

    function guardarFechas(Request $request)
    {
        $request = $request->all();

        $validator = Validator::make($request, [
            'fecha_inicio' => 'required|unique:fechas_desestimadas',
        ]);
        
        if ($validator->fails()){
            if( !isset($request['id']) || (isset($request['id']) && $request['fechaUpdate'] != $request['fecha_inicio']))
                return back()->with('message', 'La fecha de inicio ya esta registrada')->with('estatus',false);
        } 
            
        
        
        $fecha = (isset($request['id']))? FechasDesestimadas::find($request['id']) : new FechasDesestimadas();
        $fecha->anio = Carbon::createFromFormat("Y-m-d",$request['fecha_inicio'])->year;
        $fecha->fecha_inicio = $request['fecha_inicio'];
        $fecha->fecha_termino = (is_null($request['fecha_termino']))? $request['fecha_inicio'] : $request['fecha_termino'];
        $fecha->descripcion = $request['descripcion'];

        if($fecha->save())
        {
            return back()->with('message', 'Se guardó la fecha con éxito')->with('estatus',true);
        }
       
        return back()->with('message', 'Hubo un error al guardar la fecha')->with('estatus',false);
    }

    function obtenerFecha(Request $request){
        return [
            "fecha" => FechasDesestimadas::find($request->input("id"))
        ];
    }

    function eliminarFecha($id){
        
        if(FechasDesestimadas::destroy($id))
        {
            return back()->with('message', 'Se elimino la fecha con éxito')->with('estatus',true);
        }
       
        return back()->with('message', 'Hubo un error al eliminar la fecha')->with('estatus',false);

    }

    function obtenerFechasPorAño(Request $request)
    {
        return [
            "fechas" => FechasDesestimadas::where("anio", $request->input("año"))->orderBy("fecha_inicio","asc")->get()
        ];
    }
}
