<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model {
    protected $table = 'player';
    protected $fillable = ['name'];

    public function tournaments() {
        return $this->belongsToMany('App\Tournament', 'player_tournament');
    }

    public function user() {
        return $this->hasOne(User::class, 'player_id');
    }
}