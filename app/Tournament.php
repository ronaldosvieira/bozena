<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model {
    use SoftDeletes;

    protected $table = 'tournament';
    protected $fillable = ['name'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function players() {
        return $this->belongsToMany('App\Player', 'player_tournament')
            ->withPivot('team');
    }

    public function state() {
        return $this->belongsTo(TournamentState::class, 'tournament_state_id');
    }

    public function matches() {
        return $this->hasMany(Match::class, 'tournament_id');
    }

    public function isActive() {
        return $this->state->id == 2;
    }

    public function activate() {
        if ($this->state->id == 1) {
            $this->tournament_state_id = 2;
        }

        $this->save();

        return $this;
    }
}