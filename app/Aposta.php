<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aposta extends Model
{
    //
    protected $fillable = ['valor_aposta','nome_apostador','cpf'];
    
    public function jogo()
    {
        return $this->belongsToMany('App\Jogo');
    }
}
