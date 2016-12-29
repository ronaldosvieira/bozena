<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model {
    protected $table = 'tournament';
    protected $fillable = ['name'];

    public function players() {
        return $this->hasMany('App\Player', 'player_tournament');
    }
}