<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PlayerController extends Controller {
    public function index(Request $request) {
        $players = Player::all();

        return view('player.index', compact('players'));
    }

    public function create(Request $request) {
        return view('player.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255|unique:player,name',
        ]);

        Player::create([
            'name' => $request->name
        ]);

        return Redirect::route('player.index')
            ->with('message', 'Jogador adicionado com sucesso.');
    }

    public function edit(Player $player, Request $request) {
        return view('player.edit', compact('player'));
    }

    public function update(Player $player, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255|unique:player,name,' . $player->id,
        ], [
            'name.unique' => 'Já existe um jogador com o nome informado.'
        ]);

        $player->update([
            'name' => $request->name
        ]);

        return Redirect::route('player.index')
            ->with('message', 'Jogador adicionado com sucesso.');
    }

    public function destroy(Player $player, Request $request) {
        if ($player->user) {
            return Redirect::back()->with('error', 'Não é possível excluir um jogador vinculado a um usuário.');
        }

        if (!$player->tournaments->isEmpty()) {
            return Redirect::back()->with('error', 'Não é possível excluir um jogador que participou de um torneio.');
        }

        $player->delete();

        return Redirect::back()->with('message', 'O jogador ' . $player->name . ' foi excluído com sucesso.');
    }
}
