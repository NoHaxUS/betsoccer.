<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    'App\Model' => 'App\Policies\ModelPolicy',
    'App\Jogo' => 'App\Policies\JogoPolicy',
    'App\Aposta' => 'App\Policies\ApostaPolicy',
    'App\Campeonato' => 'App\Policies\CampeonatoPolicy',
    'App\Time' => 'App\Policies\TimePolicy',
    'App\User' => 'App\Policies\UserPolicy',

    
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
