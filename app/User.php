<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','role',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeBuscarPorCodigoSeguranca($query, $codigo)
    {
        return $query->where('codigo_seguranca', $codigo)->get();       //Busca usuário por codigo de segurança
    }
}
