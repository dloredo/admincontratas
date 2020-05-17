<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CapitalController extends Controller
{
    function index()
    {
        return view("capital.capital");
    }
}
