<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
	protected $table = 'jogos';
    protected $fillable = ['horarios_id','time_casa_id','time_fora_id','valor_casa','valor_empate','valor_fora','valor_1_2','valor_dupla','max_gol_2','min_gol_3','ambas_gol','campeonatos_id'];


    public function campeonatos(){
   		return $this->belongsTo('App\Campeonato');

   	}
   	public function horario(){

   		return $this->belongsTo('App\Horario');
   	}
   	public function time(){
   		return $this->belongsToMany('App\Time','times','time_casa_id','time_fora_id');

   	}
}
 