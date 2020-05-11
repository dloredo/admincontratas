<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\RequestResponse;

class Prueba extends Controller
{
    function index(RequestResponse $response)
    {
        return json_encode($response);
    }
}
