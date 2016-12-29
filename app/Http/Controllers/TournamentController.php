<?php

namespace App\Http\Controllers;

use App\Player;
use App\Tournament;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class TournamentController extends Controller {
    public function index() {
        $tournaments = Tournament::all();

        return view('tournament.index', compact('tournaments'));
    }

    public function create() {
        $players = Player::all();

        return view('tournament.create', compact('players'));
    }

    public function store() {
        $this->validate(Input::all(), [
            'name' => 'required|max:255',
        ]);

        Tournament::create([
            'name' => Input::get('name')
        ]);

        return Redirect::route('tournament.index')
            ->with('message', 'Torneio criado com sucesso');
    }

    public function edit() {}

    public function update() {}

    public function destroy() {}
}
