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

        $tournament->load('matches.goals', 'matches.state');

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

    public function fetch(Tournament $tournament, Request $request) {
        return response()->json([
            'tournament_id' => $tournament->id,
            'players' => $this->fetchPlayers($tournament),
            'matches' => $this->fetchMatches($tournament),
            'goals' => $this->fetchGoals($tournament),
            'assists' => $this->fetchAssists($tournament)
        ]);
    }

    private function fetchPlayers(Tournament $tournament) {
        return $tournament->players/*->sortByDesc(function($player) use ($tournament) {
            return $tournament->activeMatches->filter(function($match) use ($player) {
                    return ($match->home_player_id == $player->id
                            && ($match->goals->where('team', 'HOME')->count()
                                > $match->goals->where('team', 'AWAY')->count()))
                        || ($match->away_player_id == $player->id
                            && ($match->goals->where('team', 'AWAY')->count()
                                > $match->goals->where('team', 'HOME')->count()));
                })->count() * 3
                +
                $tournament->activeMatches->filter(function($match) use ($player) {
                    return ($match->home_player_id == $player->id
                            && ($match->goals->where('team', 'HOME')->count()
                                == $match->goals->where('team', 'AWAY')->count()))
                        || ($match->away_player_id == $player->id
                            && ($match->goals->where('team', 'AWAY')->count()
                                == $match->goals->where('team', 'HOME')->count()));
                })->count();
        })*/->transform(function($player) use ($tournament) {
            return [
                'name' => $player->name,
                'team' => $player->pivot->team,
                'matches' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id
                            || $match->away_player_id == $player->id;
                    })->count(),
                'won' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                                && ($match->goals->where('team', 'HOME')->count())
                                > $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                                && ($match->goals->where('team', 'AWAY')->count())
                                > $match->goals->where('team', 'HOME')->count());
                    })->count(),
                'draw' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                                && ($match->goals->where('team', 'HOME')->count())
                                == $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                                && ($match->goals->where('team', 'AWAY')->count())
                                == $match->goals->where('team', 'HOME')->count());
                    })->count(),
                'lost' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                                && ($match->goals->where('team', 'HOME')->count())
                                < $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                                && ($match->goals->where('team', 'AWAY')->count())
                                < $match->goals->where('team', 'HOME')->count());
                    })->count(),
                'goals_for' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'HOME')->count();
                    })
                    +
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->away_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'AWAY')->count();
                    }),
                'goals_against' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'AWAY')->count();
                    })
                    +
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->away_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'HOME')->count();
                    }),
                'goal_diff' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'HOME')->count();
                    })
                    +
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->away_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'AWAY')->count();
                    })
                    -
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'AWAY')->count();
                    })
                    -
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->away_player_id == $player->id;
                    })->reduce(function($carry, $match) {
                        return $carry + $match->goals
                                ->where('team', 'HOME')->count();
                    }),
                'points' => $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                                && ($match->goals->where('team', 'HOME')->count()
                                    > $match->goals->where('team', 'AWAY')->count()))
                            || ($match->away_player_id == $player->id
                                && ($match->goals->where('team', 'AWAY')->count()
                                    > $match->goals->where('team', 'HOME')->count()));
                    })->count() * 3
                    +
                    $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                                && ($match->goals->where('team', 'HOME')->count())
                                == $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                                && ($match->goals->where('team', 'AWAY')->count())
                                == $match->goals->where('team', 'HOME')->count());
                    })->count()
            ];
        })/*->sortByDesc('points')*/;


    }

    private function fetchMatches(Tournament $tournament) {
        return DB::table('match as m')
            ->select('m.id as id', 'm.week as week_num',
                'ms.name as state', 'ms.can_add_goals as can_add_goals',
                'ms.is_done as is_done', 'ms.is_started as is_started',
                'hpt.team as home_team', 'apt.team as away_team',
                DB::raw('(' . DB::table('goal as g')
                    ->select(DB::raw('count(*)'))
                    ->whereColumn('g.match_id', 'm.id')
                    ->whereRaw('g.team = \'HOME\'')
                    ->toSql() . ') as home_score'),
                DB::raw('(' . DB::table('goal as g')
                    ->select(DB::raw('count(*)'))
                    ->whereColumn('g.match_id', 'm.id')
                    ->whereRaw('g.team = \'AWAY\'')
                    ->toSql() . ') as away_score'))
            ->leftJoin('match_state as ms', 'm.match_state_id', '=', 'ms.id')
            ->leftJoin('player_tournament as hpt', function ($join) {
                $join->on('hpt.player_id', '=', 'm.home_player_id')
                    ->where('hpt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->leftJoin('player_tournament as apt', function ($join) {
                $join->on('apt.player_id', '=', 'm.away_player_id')
                    ->where('apt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->where('m.tournament_id', $tournament->id)
            ->orderBy('m.id')
            ->get()
            ->groupBy('week_num');
    }

    private function fetchGoals(Tournament $tournament) {
        return DB::table('goal as g')
            ->select('g.scorer as name',
                DB::raw('CASE WHEN g.team = \'HOME\' THEN hpt.team ELSE apt.team END'),
                DB::raw('count(*) as goals'))
            ->leftJoin('match as m', 'm.id', '=', 'g.match_id')
            ->leftJoin('player_tournament as hpt', function ($join) {
                $join->on('hpt.player_id', '=', 'm.home_player_id')
                    ->where('hpt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->leftJoin('player_tournament as apt', function ($join) {
                $join->on('apt.player_id', '=', 'm.away_player_id')
                    ->where('apt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->where('m.tournament_id', '=', $tournament->id)
            ->groupBy('g.scorer', 'g.team', 'hpt.team', 'apt.team')
            ->get()
            ->groupBy('name')
            ->map(function ($goals) {
                return $goals->reduce(function($carry, $goal) {
                    if ($carry) $carry->goals += $goal->goals;
                    else $carry = $goal;

                    return $carry;
                });
            })
            ->values()
            /*->sortByDesc('goals')*/;
    }

    private function fetchAssists(Tournament $tournament) {
        return DB::table('goal as g')
            ->select('g.assister as name',
                DB::raw('CASE WHEN g.team = \'HOME\' THEN hpt.team ELSE apt.team END'),
                DB::raw('count(*) as assists'))
            ->leftJoin('match as m', 'm.id', '=', 'g.match_id')
            ->leftJoin('player_tournament as hpt', function ($join) {
                $join->on('hpt.player_id', '=', 'm.home_player_id')
                    ->where('hpt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->leftJoin('player_tournament as apt', function ($join) {
                $join->on('apt.player_id', '=', 'm.away_player_id')
                    ->where('apt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
            })
            ->where('m.tournament_id', '=', $tournament->id)
            ->whereNotNull('g.assister')
            ->groupBy('g.assister', 'g.team', 'hpt.team', 'apt.team')
            ->get()
            ->groupBy('name')
            ->map(function ($goals) {
                return $goals->reduce(function($carry, $goal) {
                    if ($carry) $carry->assists += $goal->assists;
                    else $carry = $goal;

                    return $carry;
                });
            })
            ->values()
            /*->sortByDesc('assists')*/;
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
