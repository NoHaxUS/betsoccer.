<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acerto extends Model
{
    protected $fillable = ['cambista_id','gerente_id','qtd_apostas','qtd_jogos','comissao_simples','comissao_mediana','comissao_maxima','total_apostado','total_premiacao','liquido'];

    public function cambista()
    {
        return $this->belongsTo('App\User', 'cambista_id');
    }
    public function gerente()
    {
        return $this->belongsTo('App\User', 'gerente_id');
    }
}
