<?php

namespace App\Http\Controllers;

use App\Match;
use App\Tournament;
use Illuminate\Support\Facades\Redirect;

class MatchController extends Controller {
    public function start(Tournament $tournament, Match $match) {
        if (!$tournament->exists || !$match->exists) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        if (!$tournament->matches->contains($match)) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        $match->start();

        return Redirect::route('tournament.show', [
            'tournament' => $tournament->id,
            'match' => $match->id
        ])->with('message', 'Partida ' . $match->homePlayer->name .' x ' . $match->awayPlayer->name .' iniciada.');
    }

    public function end(Tournament $tournament, Match $match) {
        if (!$tournament->exists || !$match->exists) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        if (!$tournament->matches->contains($match)) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        $match->end();

        return Redirect::route('tournament.show', [
            'tournament' => $tournament->id,
            'match' => $match->id
        ])->with('message', 'Partida ' . $match->homePlayer->name .' x ' . $match->awayPlayer->name .' terminada.');
    }
}
