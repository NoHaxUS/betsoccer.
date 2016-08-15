<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
	protected $table = 'jogos';
    protected $fillable = ['time_casa','time_fora','valor_casa','valor_fora','valor_gol','valor_empate','valor_dupla','max_gol_2','min_gol_3','ambas_gol','campeonatos_id'];


    	public function campeonatos(){
   		return $this->belongsTo('App\Campeonato');

   	}
}
 