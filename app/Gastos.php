<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categorias;
use App\Subcategorias;

class Gastos extends Model
{
    public $timestamps = false;
    protected $table = "gastos";
    protected $fillable = [
        'cantidad' , 'categoria' , 'informacion' , 'fecha_gasto','id_user','categoria_id','subcategoria_id '
    ];


    public function categoria_r()
    {
        return $this->belongsTo(Categorias::class, 'categoria_id', 'id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategorias::class, 'subcategoria_id', 'id');
    }

    public function scopeCategoria($query, $categoria)
    {
        if($categoria)
        {
            return $query->where('categoria_id' , $categoria);
        }
    }

    public function scopeSubcategoria($query, $subcategoria)
    {
        if($subcategoria)
        {
            return $query->where('subcategoria_id' , $subcategoria);
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
