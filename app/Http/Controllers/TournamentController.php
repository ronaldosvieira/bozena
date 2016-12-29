<?php

namespace App\Http\Controllers;

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

    public function show(Tournament $tournament, Request $request) {}

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
}
