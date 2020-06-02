<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

  

    protected $table = "usuarios";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password',"nombres","apellidos","direccion","telefono","id_rol","activo"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //Scopes

    function scopeActivo($query)
    {
        $query->where("activo",true);
    }

    function scopeCobrador($query)
    {
        $query->where("id_rol",2);
    }

    function scopeAdmin($query)
    {
        $query->where("id_rol",1);
    }


    //Relations
    function rol()
    {
        return $this->belongsTo('App\Rol',"id_rol");
    }

    function clientes()
    {
        return $this->hasMany('App\Clientes',"cobrador_id");
    }

    function contratasAsignadas()
    {
        return $this->hasManyThrough(Contratas::class,CLientes::class,"cobrador_id","id_cliente");
    }
    
    public function pagosClientes()
    {
        return $this->hasManyDeep(
            'App\PagosContratas',
            ['App\Clientes', 'App\Contratas'], // Intermediate models, beginning at the far parent (Country).
            [
               'cobrador_id', // Foreign key on the "Clientes" table.
               'id_cliente',    // Foreign key on the "Contratas" table.
               'id_contrata'     // Foreign key on the "PagosContratas" table.
            ],
            [
              'id', // Local key on the "usuarios" table.
              'id', // Local key on the "clientes" table.
              'id'  // Local key on the "contratas" table.
            ]
        );
    }


    //Accesor
    function getActivoAttribute($value)
    {
        if($value)
            return "Activo";

        return "Inactivo";
    }
}
