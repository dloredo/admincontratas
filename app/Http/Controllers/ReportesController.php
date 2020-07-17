<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clientes;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporteDirectorios()
    {
        $clientes = Clientes::all();
        $pdf = \PDF::loadView('reportes.directorios' ,  compact('clientes'));
        $fecha = Carbon::now();
        return $pdf->download('Directorios.pdf');
    }
}
