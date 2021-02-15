<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesHabilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    public function index()
    {
        $clienteshabiles = DB::select('select * from clientes c2 
        left join
        (select count(estatus) as total , estatus ,id_cliente , cantidad_prestada,fecha_termino,comision from
        contratas c
        where estatus = 0
        group by estatus ,id_cliente) as cd on cd.id_cliente = c2.id 
        where estatus is null'); 
        //dd($clienteshabiles);
        return view ('clientes.numerosHabiles' ,  compact('clienteshabiles'));
    }
}
