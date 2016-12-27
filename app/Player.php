<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    protected $table = 'player';

    public function tournaments() {
        return $this->hasMany('App\Tournament', 'player_tournament');
    }
}