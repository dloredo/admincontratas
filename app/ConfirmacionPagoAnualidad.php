<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfirmacionPagoAnualidad extends Model
{
    protected $fillable = ["id_cobrador", "id_contrata", "fecha_anualidad"];
    public $timestamps = false;
}
