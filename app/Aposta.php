<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
    //
    protected $fillable = ['valor_aposta', 'nome_apostador','pago', 'users_id'];

    //metodo que retorna jogos
    public function jogo()
    {
        return $this->belongsToMany('App\Jogo', 'aposta_jogo', 'apostas_id', 'jogos_id')->withPivot('palpite', 'tpalpite')->withTimestamps();
    }

    //metodo que retorna os usuários
    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    /**Método que busca apostas com menos de 7 dias de um determinado usuário
     * @param $query
     * @param $user_id int id do usuário
     * @return mixed lista de apostas dos últimos 7 dias
     */
    public function scopeRecentes($query, $user_id)
    {
        return $query->where('users_id', $user_id)
            ->whereDate('created_at', '>=', \Carbon\Carbon::now()->subDay(7))->get();
    }


}
