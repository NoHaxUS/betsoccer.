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

    protected $fillable = ['name', 'email', 'password', 'role', 'ativo', 'codigo_seguranca'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** Consulta de usuário por código de segurança
     * @param $query
     * @param $codigo string código de segurança
     * @return mixed usuário com código de segurança especificado
     */
    public function scopeBuscarPorCodigoSeguranca($query, $codigo)
    {
        return $query->where('codigo_seguranca', $codigo)->get();       //Busca usuï¿½rio por codigo de seguranï¿½a
    }

    /** Consulta apostas feitas
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apostas()
    {
        return $this->hasMany('App\Aposta', 'users_id');
    }
}