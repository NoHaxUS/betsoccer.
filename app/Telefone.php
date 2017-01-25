<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $fillable = ['ddd', 'numero', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}