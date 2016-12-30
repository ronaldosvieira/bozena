<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchState extends Model {
    protected $table = 'match_state';
    protected $fillable = ['name', 'can_add_goals', 'is_done'];
}
