<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    function index()
    {
        return view("principal.principal");
    }
}
