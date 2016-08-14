<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
	protected $fillable = ['descricao_time','campeonato_id'];

    public function campeonatos(){
    	return $this->belongsTo('App\Campeonato','campeonato_id');
    }
  

   public function addCampeonato(Campeonato $cam){
    	return $this->campeonatos()->save($cam);

   }

   public function deletarTime(){
   	foreach($this->campeonatos as $camp){
   		$camp->delete();
   	}
   	return true;

   }
}
