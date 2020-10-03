<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialCobrosDia extends Model
{
    public $timestamps = false;
    protected $table = "historial_cobros_dia";
    protected $fillable = [
        'id_cobrador' , 'cantidad' , 'fecha','id_contrata','id_cliente'
    ];


    function Cobrador()
    {
        return $this->hasOne('App\User',"id","id_cobrador");
    }

    function Contrata()
    {
        return $this->hasOne('App\Contratas',"id","id_contrata");
    }

    function Cliente()
    {
        return $this->hasOne('App\Clientes',"id","id_cliente");
    }
}
