<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\RequestResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PagosDiariosExportBook; 
use Illuminate\Support\Facades\Storage;
use App\PagosContratas;
class Prueba extends Controller
{

    public $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

    function index()
    {

        //return $this->pruebaMesCarbon();
        $this->pruebaExcel();
    }

    function pruebaMesCarbon()
    {
        //Regresa el nombre mes
        return $this->meses[Carbon::now()->format("n") - 1];
    }

    function pruebaExcel()
    {
        return "ninguna prueba";
        
    }
}
