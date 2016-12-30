<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model {
    protected $table = 'goal';
    protected $fillable = ['match_id', 'team', 'scorer', 'assister'];
    public $timestamps = false;

    public function match() {
        return $this->belongsTo('App\Match', 'match_id');
    }
}
