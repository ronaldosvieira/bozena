<?php

namespace App\Http\Controllers;

use App\Tournament;

class TournamentController extends Controller {
    public function index() {
        $tournaments = Tournament::all();

        return view('tournament.index', compact('tournaments'));
    }

    public function create() {}

    public function store() {}

    public function edit() {}

    public function update() {}

    public function destroy() {}
}
