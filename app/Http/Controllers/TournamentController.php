<?php

namespace App\Http\Controllers;

use App\Match;
use App\Player;
use App\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TournamentController extends Controller {
    public function index(Request $request) {
        $tournaments = Tournament::all();

        return view('tournament.index', compact('tournaments'));
    }

    public function show(Tournament $tournament, Request $request) {
        $cores = ['red', 'blue', 'green', 'yellow'];

        $tournament->load('matches.goals');

        return view('tournament.show', compact('tournament', 'cores'));
    }

    public function create(Request $request) {
        $players = Player::all();

        return view('tournament.create', compact('players'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'player.*' => 'integer|exists:player,id'
        ]);

        DB::transaction(function() use($request) {
            $tournament = Tournament::create([
                'name' => $request->name
            ]);

            $tournament->players()->sync($request->player_id);
        });

        return Redirect::route('tournament.index')
            ->with('message', 'Torneio criado com sucesso.');
    }

    public function edit(Tournament $tournament, Request $request) {
        if (!$tournament->state->can_edit) {
            return Redirect::route('tournament.index')
                ->with('error', 'O torneio ' . $tournament->name .' não pode ser editado.');
        }

        $players = Player::all();
        $tournament->player_id = $tournament->players->pluck('id')->toArray();

        return view('tournament.edit', compact('players', 'tournament'));
    }

    public function update(Tournament $tournament, Request $request) {
        if (!$tournament->state->can_edit) {
            return Redirect::route('tournament.index')
                ->with('error', 'O torneio ' . $tournament->name .' não pode ser editado.');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'player.*' => 'integer|exists:player,id'
        ]);

        DB::transaction(function() use($request, $tournament) {
            $tournament->update([
                'name' => $request->name
            ]);

            $tournament->players()->sync($request->player_id);
        });

        return Redirect::route('tournament.index')
            ->with('message', 'Torneio criado com sucesso.');
    }

    public function destroy(Tournament $tournament, Request $request) {}

    public function activate(Tournament $tournament, Request $request) {
        $this->validate($request, [
            'team.*' => 'required|max:255',
        ], [
            'team.*.required' => 'Para ativar o torneio, é necessário que todos os times estejam preenchidos.',
            'team.*.max' => 'O nome do time deve conter até 255 caracteres.'
        ]);

        DB::transaction(function() use ($request, $tournament) {
            foreach ($request->team as $id => $team) {
                $tournament->players()->updateExistingPivot($id, ['team' => $team]);
            }

            if ($tournament->players->count() < 2) {
                return Redirect::back()
                    ->with('error', 'Não é possível ativar um torneio com menos de dois jogadores.');
            }

            $isComplete = $tournament->players()->withPivot('team')->get()
                ->filter(function($player) {
                    return is_null($player->pivot->team);
                })
                ->isEmpty();

            if ($isComplete) {
                $this->generateMatches($tournament);
                $tournament->activate();
            }
        });

        return Redirect::route('tournament.show', $tournament->id)
            ->with('message', 'Torneio ativado com sucesso.');
    }

    private function generateMatches(Tournament $tournament) {
        $tournament->load('players');

        $players = $tournament->players->shuffle()->chunk(2);
        $firstTurn = collect();
        $secondTurn = collect();

        $groupA = $players->get(0)->pluck('id');
        $groupB = $players->get(1)->pluck('id');

        if (!is_null($players->get(2))) {
            $groupA->push($players->get(2)->first()->id);
            $groupB->push(0);
        }

        for ($week = 1; $week < $tournament->players->count(); $week++) {
            for ($match = 0; $match < $groupA->count(); $match++) {
                if (!$groupA->get($match) || !$groupB->get($match))
                    continue;

                $firstTurn->push(new Match([
                    'week' => $week,
                    'tournament_id' => $tournament->id,
                    'home_player_id' => $groupA->get($match),
                    'away_player_id' => $groupB->get($match)
                ]));

                $secondTurn->push(new Match([
                    'week' => $week + $tournament->players->count() - 1,
                    'tournament_id' => $tournament->id,
                    'home_player_id' => $groupB->get($match),
                    'away_player_id' => $groupA->get($match)
                ]));
            }

            $groupA->put($groupA->count(), $groupA->get(1));
            $groupA->put(1, $groupB->shift());
            $groupB->push($groupA->pop());
        }

        $matches = $firstTurn->merge($secondTurn);

        $matches->each(function ($match) {
            $match->save();
        });
    }
}
