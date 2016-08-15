<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    // 
    protected $fillable = ['data'];

    	public function jogo(){
   		return $this->hasMany('App\Jogo');

   	}
}
