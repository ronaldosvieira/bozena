<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Match;
use App\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class MatchController extends Controller {
    public function start(Tournament $tournament, Request $request) {
        $match = Match::find($request->get('match_id'));

        if (!$tournament->exists || is_null($match) || !$match->exists) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
        }

        if (!$tournament->matches->contains($match)) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
        }

        $match->start();

        return Response::json(['success' => true, 'error' => []], 200);
    }

    public function end(Tournament $tournament, Request $request) {
        $match = Match::find($request->get('match_id'));

        if (!$tournament->exists || is_null($match) || !$match->exists) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
        }

        if (!$tournament->matches->contains($match)) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
        }

        $match->end();

        return Response::json(['success' => true, 'error' => []], 200);
    }

    public function addGoal(Tournament $tournament, Request $request) {
        $match = Match::find($request->get('match_id'));

        if (!$tournament->exists || is_null($match) || !$match->exists || !$match->state->can_add_goals) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
        }

        if (!$tournament->matches->contains($match)) {
            return Response::json(['success' => false, 'error' => 'Partida inválida'], 200);
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
            'assister' => $request->assister?: null
        ]);

        return Response::json(['success' => true, 'error' => []], 200);
    }
}
