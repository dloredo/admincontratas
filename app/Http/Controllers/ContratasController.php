<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contratas;
use App\Clientes;
use Illuminate\Support\Facades\Auth;

class ContratasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if(Auth::user()->id_rol != 1)
        {
            $contratas = Contratas::contratasAsignadas(Auth::user()->id)->get();
        }
        else
        {
            $contratas = Contratas::with("cliente")->get();
        }
        
        return view('contratas.contratas' , compact( 'contratas'));
    }
}
