<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model {
    protected $table = 'goal';

    public function match() {
        return $this->belongsTo('App\Match', 'match_id');
    }
}
