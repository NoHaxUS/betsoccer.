<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
	protected $fillable = ['descricao_time'];

  public function jogos()
  {
      return $this->belongsToMany('App\Jogo','jogo_time','jogos_id','times_id');
  }
/**
    public function campeonatos(){
    	return $this->belongsTo('App\Campeonato');
    }
   public function addCampeonato(Campeonato $cam){
    	return $this->campeonatos()->save($cam);
   }
   public function deletarTime(){
   	foreach($this->campeonatos as $camp){
   		$camp->delete();
   	}
   	return true;
*/
}
