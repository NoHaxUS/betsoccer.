<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
    //
    protected $fillable = ['valor_aposta','nome_apostador','cpf','users_id'];
    
    //metodo que retorna jogos
    public function jogo()
    {
        return $this->belongsToMany('App\Jogo','aposta_jogo','apostas_id','jogos_id')->withPivot('palpite');
    }
    
    //metodo que retorna os usuários
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
