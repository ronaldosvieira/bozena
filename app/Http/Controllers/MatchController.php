<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Match;
use App\Tournament;
use Illuminate\Http\Request;
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

        return Redirect::route('tournament.show', $tournament->id)
            ->with('message', 'Partida ' . $match->homePlayer->name .' x ' . $match->awayPlayer->name .' iniciada.');
    }

    public function end(Tournament $tournament, Match $match) {
        if (!$tournament->exists || !$match->exists) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        if (!$tournament->matches->contains($match)) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        $match->end();

        return Redirect::route('tournament.show', $tournament->id)
            ->with('message', 'Partida ' . $match->homePlayer->name .' x ' . $match->awayPlayer->name .' terminada.');
    }

    public function addGoal(Tournament $tournament, Match $match, Request $request) {
        if (!$tournament->exists || !$match->exists || !$match->state->can_add_goals) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        if (!$tournament->matches->contains($match)) {
            return Redirect::back()->with('error', 'Partida inválida.');
        }

        $this->validate($request, [
            'team' => 'required|string|in:HOME,AWAY',
            'scorer' => 'required|string|max:255',
            'assister' => 'nullable|string|max:255'
        ], [
            'team.required' => 'É necessário informar o jogador ao adicionar gol.',
            'team.string' => 'Jogador inválido.',
            'team.in' => 'Jogador inválido.',
            'scorer.required' => 'É necessário informar o autor do gol.',
            'scorer.string' => 'Autor do gol inválido.',
            'scorer.max' => 'O nome do autor do gol deve conter até 255 caracteres.',
            'assister.string' => 'Autor da assistência inválido.',
            'assister.max' => 'O nome do autor da assistência deve conter até 255 caracteres.',
        ]);

        Goal::create([
            'match_id' => $match->id,
            'team' => $request->team,
            'scorer' => $request->scorer,
            'assister' => $request->assister
        ]);

        return Redirect::route('tournament.show', $tournament->id)
            ->with('message', 'Gol da partida ' .
                $match->homePlayer->name . ' x ' . $match->awayPlayer->name . ' inserido com sucesso.');
    }
}
