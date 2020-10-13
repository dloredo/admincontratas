<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategorias extends Model
{
    protected $table = "subcategorias";
    protected $fillable = [
        'id_categoria' , 'sub_categoria' 
    ];
}
