<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    public $timestamps = false;
    protected $table = "gastos";
    protected $fillable = [
        'cantidad' , 'categoria' , 'informacion' , 'fecha_gasto','id_user'
    ];

    public function scopeCategoria($query, $categoria)
    {
        if($categoria)
        {
            return $query->where('categoria' , 'LIKE' , "%$categoria%");
        }
    }

    public function scopeUser($query, $user_id)
    {
        if($user_id)
        {
            return $query->where('id_user'  , $user_id);
        }
    }
}
