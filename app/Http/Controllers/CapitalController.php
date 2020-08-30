<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Capital;
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
        $prestamos_totales = Contratas::where('estatus' , 0)->sum('cantidad_prestada');
        $pagos_totales = PagosContratas::sum('cantidad_pagada');
        $comisiones = Contratas::sum('comision');
        //dd($pagos_totales);
        return view("capital.capital", ['prestamos_totales' => $prestamos_totales ,'pagos_totales' => $pagos_totales, 'comisiones' => $comisiones], compact("capital","cortes"));
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

        $cortes->save();
        $capital->save();

        return back();
    }


    function movimientosCapital()
    {
        $capital = Capital::find(1);
        $movimientos = Movimiento::all();
        $prestamos_totales = Contratas::where('estatus' , 0)->sum('cantidad_prestada');
        $pagos_totales = PagosContratas::sum('cantidad_pagada');
        $comisiones = Contratas::sum('comision');
        return view("capital.capital" ,['prestamos_totales' => $prestamos_totales ,'pagos_totales' => $pagos_totales, 'comisiones' => $comisiones], compact("capital","movimientos"));
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
