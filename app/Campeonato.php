<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
	protected $fillable = ['id','descricao_campeonato'];
	
   	public function time(){
   		return $this->belongsTo('App\Time');

   	}

   	public function jogo(){
   		return $this->hasMany('App\Jogo');

   	}
}
