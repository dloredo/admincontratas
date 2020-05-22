<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;

class ContratasController extends Controller
{
    public function index()
    {
        $contratas = Contratas::all();
        $clientes = Clientes::all();
        return view('contratas.contratas' , compact('clientes' , 'contratas'));
    }
}
