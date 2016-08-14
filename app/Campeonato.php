<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
	protected $fillable = ['descricao_campeonato'];
	
   	public function time(){
   		return $this->hasMany('App\Time');

   	}

   	public function jogo(){
   		return $this->hasMany('App\Jogo');

   	}
}
