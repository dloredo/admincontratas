<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Capital;
use App\Clientes;
use App\Cortes;
use App\Movimiento;
use App\Contratas;
use App\PagosContratas;
use Illuminate\Support\Facades\Validator;

class CapitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    
    function index()
    {
        $capital = Capital::find(1);
        $cortes = Cortes::all();
        $prestamos_totales = Contratas::where('estatus' , 0)->sum('cantidad_pagar');
        $pagos_totales = PagosContratas::sum('cantidad_pagada');
        $comisiones = Contratas::sum('comision');
        $contratas_vigentes = Contratas::where('estatus' , 0)->count();
        $parcial = Clientes::selectRaw(" (contratas.cantidad_pagar - sum(pagos_contratas.cantidad_pagada)) as parcial ")
        ->join("contratas" , "clientes.id","contratas.id_cliente")
        ->join("pagos_contratas" , "contratas.id","pagos_contratas.id_contrata")
        ->groupBy("contratas.id")
        ->get();
        $clientes = Clientes::count();
        //dd($pagos_totales);
        return view("capital.capital", ['prestamos_totales' => $prestamos_totales ,'pagos_totales' => $pagos_totales, 'comisiones' => $comisiones , 'contratas_vigentes' => $contratas_vigentes , 'clientes' => $clientes], compact("capital","cortes","parcial"));
    }

    function generarCorte()
    {
        $capital = Capital::find(1);

        $cortes = new Cortes();
        $cortes->capital_acumulado = $capital->capital_acumulado;
        $cortes->saldo_efectivo = $capital->saldo_efectivo;
        $cortes->capital_parcial = $capital->capital_parcial;
        $cortes->comisiones = $capital->comisiones;

        $capital->capital_acumulado += $capital->comisiones;
        $capital->saldo_efectivo += $capital->comisiones;
        $capital->comisiones = 0;
        $capital->capital_acumulado -= $capital->gastos;
        $capital->gastos = 0;
        $cortes->save();
        $capital->save();

        return back();
    }

    public function generarCorteGastos(Request $request)
    {
        Cortes::create([
            'clientes'          => $request['clientes'],
            'contratas'         => $request['contratas'],
            'prestamos_totales' => $request['prestamos_totales'],
            'gastos'            => $request['gastos'],
            'capital_acumulado' => $request['capital_acumulado'],
            'comisiones'        => $request['comisiones'],
            'capital_parcial'   => $request['capital_parcial'],
            'saldo_efectivo'    => $request['saldo_efectivo'],
            'capital_total'     => $request['capital_total'],
        ]);
        
        $capital = Capital::find(1);
        $capital->capital_acumulado -= $request['gastos'];
        $capital->gastos = 0;
        $capital->save();

        return back();
    }

    public function generarCorteComisionesGastos(Request $request)
    {
        Cortes::create([
            'clientes'          => $request['clientes'],
            'contratas'         => $request['contratas'],
            'prestamos_totales' => $request['prestamos_totales'],
            'gastos'            => $request['gastos'],
            'capital_acumulado' => $request['capital_acumulado'],
            'comisiones'        => $request['comisiones'],
            'capital_parcial'   => $request['capital_parcial'],
            'saldo_efectivo'    => $request['saldo_efectivo'],
            'capital_total'     => $request['capital_total'],
        ]);
        
        $capital = Capital::find(1);
        $capital->capital_acumulado -= $request['gastos'];
        $capital->capital_acumulado += $request['comisiones'];
        $capital->gastos = 0;
        $capital->comisiones = 0;
        $capital->save();

        return back();
    }

    public function generarCorteComisiones(Request $request)
    {
        Cortes::create([
            'clientes'          => $request['clientes'],
            'contratas'         => $request['contratas'],
            'prestamos_totales' => $request['prestamos_totales'],
            'gastos'            => $request['gastos'],
            'capital_acumulado' => $request['capital_acumulado'],
            'comisiones'        => $request['comisiones'],
            'capital_parcial'   => $request['capital_parcial'],
            'saldo_efectivo'    => $request['saldo_efectivo'],
            'capital_total'     => $request['capital_total'],
        ]);

        $capital = Capital::find(1);
        $capital->capital_acumulado += $capital->comisiones;
        $capital->comisiones = 0;
        $capital->save();

        return back();
    }


    function movimientosCapital()
    {
        $capital = Capital::find(1);
        $movimientos = Movimiento::all();
        $prestamos_totales = Contratas::where('estatus' , 0)->sum('cantidad_pagar');
        $pagos_totales = PagosContratas::sum('cantidad_pagada');
        $comisiones = Contratas::sum('comision');
        $contratas_vigentes = Contratas::where('estatus' , 0)->count();
        $parcial = Clientes::selectRaw(" (contratas.cantidad_pagar - sum(pagos_contratas.cantidad_pagada)) as parcial ")
        ->join("contratas" , "clientes.id","contratas.id_cliente")
        ->join("pagos_contratas" , "contratas.id","pagos_contratas.id_contrata")
        ->groupBy("contratas.id")
        ->get();
        $clientes = Clientes::count();
        return view("capital.capital", ['prestamos_totales' => $prestamos_totales ,'pagos_totales' => $pagos_totales, 'comisiones' => $comisiones , 'contratas_vigentes' => $contratas_vigentes , 'clientes' => $clientes], compact("capital","movimientos","parcial"));
    }

    function crearMovimientoCapital(Request $request)
    {
        $data = $request->all();
        $this->validator($data)->validate();
        unset($data["_token"]);

        $movimiento = new Movimiento($data);
        $movimiento->save();

        $capital = Capital::find(1);

        if($data["tipo_movimiento"] == "Abono")
        {
            $capital->capital_acumulado += $movimiento->total;
            $capital->saldo_efectivo += $movimiento->total;
        }
        else
        {
            $capital->capital_acumulado -= $movimiento->total;
            $capital->saldo_efectivo -= $movimiento->total;
        }

        $capital->save();

        return back();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tipo_movimiento' => ['required'],
            'total' => ['required', 'integer']
        ]);
    }
}
