<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlSaldos extends Model
{
    public $timestamps = false;
    protected $table = 'control_saldos';
    protected $fillable = ["cargos","abonos" ,"saldo","id_cobrador","fecha"];
}
