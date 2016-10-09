<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
class TimePolicy
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
     public function cadastrar(User $user, \App\Time $time)
    {
        return $user->role == 'admin';
    }

    public function salvar(User $user,  \App\Time $time)
    {
        return $user->role == 'admin';
    }

    public function alterar(User $user,  \App\Time $time)
    {
        return $user->role == 'admin';
    }

    public function excluir(User $user,  \App\Time $time)
    {
        return $user->role == 'admin';
    }
    public function deletar(User $user,  \App\Time $time)
    {
        return $user->role == 'admin';
    }
}
