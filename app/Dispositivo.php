<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $fillable = ['mac', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}