<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model {
    protected $table = 'match';
    protected $fillable = ['week', 'tournament_id',
        'home_player_id', 'away_player_id'];

    public function tournament() {
        return $this->belongsTo('App\Tournament', 'tournament_id');
    }

    public function homePlayer() {
        return $this->belongsTo('App\Player', 'home_player_id');
    }

    public function awayPlayer() {
        return $this->belongsTo('App\Player', 'away_player_id');
    }

    public function state() {
        return $this->belongsTo(MatchState::class, 'match_state_id');
    }

    public function goals() {
        return $this->hasMany('App\Goal', 'match_id');
    }
}