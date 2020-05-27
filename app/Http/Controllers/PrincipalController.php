<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class PrincipalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {
        if(Auth::user()->id_rol != 1)
        {
            $user = User::find(Auth::user()->id);
            //return $user->contratasAsignadas;
        }
        return view("principal.principal");
    }
}
