<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    protected $fillable = ['horario','timecasa','timefora','valorcasa','valoremp','valorfora','valorgoal','valordupla','campeonato_id'];

    	public function campeonatos(){
   		return $this->belongsTo('App\Campeonato');

   	}
}
