<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentState extends Model {
    protected $table = 'tournament_state';
    protected $fillable = ['name', 'can_edit', 'can_remove'];
}