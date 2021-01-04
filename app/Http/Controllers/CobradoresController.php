<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CobradoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function cobradores()
    {
        return view('cobradores.cobradores');
    }
    public function historial_cobradores()
    {
        return view('cobradores.cobradores');
    }
}
