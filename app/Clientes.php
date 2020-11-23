<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    public $timestamps = false;
    protected $table = "clientes";
    protected $fillable = [
        'nombres' , 'direccion' , 'telefono' , 'fecha_registro','activo','cobrador_id','colonia' , 'ciudad','telefono_2'
    ];

    function cobrador()
    {
        return $this->belongsTo('App\User',"cobrador_id");
    }


    function getActivoAttribute($value)
    {
        if($value)
            return "Activo";

        return "Inactivo";
    }
}
