<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ApostaPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
     public function cadastrar(User $user, App\Http\Apotas $Apotas)
    {
        return $user->role == 'admin' || $user->role == 'apostador';
    }

    public function salvar(User $user,  App\Http\Apotas $Apotas)
    {
        return $user->role == 'admin' || $user->role == 'apostador';
    }

    public function excluir(User $user,  App\Http\Apotas $Apotas)
    {
        return $user->role == 'admin';
    }
}
