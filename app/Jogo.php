<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use softDeletes;
class Jogo extends Model
{
	  protected $table = 'jogos';
    protected $fillable = ['data','valor_casa','valor_empate','valor_fora','valor_1_2','valor_dupla','max_gol_2','min_gol_3','ambas_gol','campeonatos_id'];


    public function campeonato(){
   		return $this->belongsTo('App\Campeonato','campeonatos_id');

   	}
   	/*public function horario(){
      $teste = Carbon::now()->addMinutes(5);
   		return $this->belongsTo('App\Horario','horarios_id')->where('data', '>', $teste);
   	}*/
   	public function time(){
   		return $this->belongsToMany('App\Time','jogo_time','jogos_id','times_id');

   	}
}