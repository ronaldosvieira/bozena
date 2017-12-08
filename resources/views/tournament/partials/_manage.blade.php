<div class="row">
@foreach ($tournament->players as $player)
    <div class="col-sm-6 col-lg-3">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-{{ $cores[$loop->iteration % count($cores)] }}">
                <div class="widget-user-image">
                    <img class="img-circle" src="{{ asset('img/user.png') }}" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{ $player->name }}</h3>
                <h5 class="widget-user-desc">
                    {{ $player->pivot->team }}
                </h5>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
@endforeach
</div>

<div class="col-xs-12 col-lg-offset-3 col-lg-6">
    <h3 class="text-center">Tabela</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th class="col-xs-4">Nome</th>
                <th class="col-xs-1 text-center">J</th>
                <th class="col-xs-1 text-center">V</th>
                <th class="col-xs-1 text-center">E</th>
                <th class="col-xs-1 text-center">D</th>
                <th class="col-xs-1 text-center">Gp</th>
                <th class="col-xs-1 text-center">Gc</th>
                <th class="col-xs-1 text-center">Sg</th>
                <th class="col-xs-1 text-center">Pts</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tournament->players->sortByDesc(function($player) use ($tournament) {
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
            })
            as $player)
            <tr>
                <td>{{ $player->name }}</td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
                        return $match->home_player_id == $player->id
                            || $match->away_player_id == $player->id;
                    })->count() }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                            && ($match->goals->where('team', 'HOME')->count())
                                > $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                            && ($match->goals->where('team', 'AWAY')->count())
                                > $match->goals->where('team', 'HOME')->count());
                    })->count() }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                            && ($match->goals->where('team', 'HOME')->count())
                                == $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                            && ($match->goals->where('team', 'AWAY')->count())
                                == $match->goals->where('team', 'HOME')->count());
                    })->count() }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
                        return ($match->home_player_id == $player->id
                            && ($match->goals->where('team', 'HOME')->count())
                                < $match->goals->where('team', 'AWAY')->count())
                            || ($match->away_player_id == $player->id
                            && ($match->goals->where('team', 'AWAY')->count())
                                < $match->goals->where('team', 'HOME')->count());
                    })->count() }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
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
                     }) }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
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
                     }) }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
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
                     }) }}
                </td>
                <td class="text-center">
                    {{ $tournament->activeMatches->filter(function($match) use ($player) {
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
                    })->count() }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@if ($tournament->matches->count())
<div class="row">
    <div class="col-lg-offset-1 col-lg-10">
        <h3 class="col-xs-12 text-center">Partidas</h3>
            @each('tournament.partials._week',
                $tournament->matches->groupBy('week')->sort(), 'matches')
    </div>
</div>
@endif

<div class="row voffset">
    <div class="col-lg-offset-1 col-lg-10"><div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Gols</h3>
        <div class="table-responsivse">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Time</th>
                    <th>Gols</th>
                </tr>
                </thead>
                <tbody>
                    @foreach (DB::table('goal as g')
                        ->select('g.scorer as name', DB::raw('CASE WHEN g.team = \'HOME\' THEN hpt.team ELSE apt.team END'), DB::raw('count(*) as goals'))
                        ->leftJoin('match as m', 'm.id', '=', 'g.match_id')
                        ->leftJoin('player_tournament as hpt', function ($join) {
                            $join->on('hpt.player_id', '=', 'm.home_player_id')
                                ->where('hpt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
                        })
                        ->leftJoin('player_tournament as apt', function ($join) {
                            $join->on('apt.player_id', '=', 'm.home_player_id')
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
                        ->sortByDesc('goals') as $scorer)
                        <tr>
                            <td>{{ $scorer->name }}</td>
                            <td>{{ $scorer->team }}</td>
                            <td>{{ $scorer->goals }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6">
        <h3 class="col-xs-12 text-center">Assistências</h3>
        <div class="table-responsivse">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>Jogador</th>
                    <th>Time</th>
                    <th>Gols</th>
                </tr>
                </thead>
                <tbody>
                @foreach (DB::table('goal as g')
                        ->select('g.assister as name',
                            DB::raw('CASE WHEN g.team = \'HOME\' THEN hpt.team ELSE apt.team END'),
                            DB::raw('count(*) as assists'))
                        ->leftJoin('match as m', 'm.id', '=', 'g.match_id')
                        ->leftJoin('player_tournament as hpt', function ($join) {
                            $join->on('hpt.player_id', '=', 'm.home_player_id')
                                ->where('hpt.tournament_id', '=', DB::raw('m.tournament_id::integer'));
                        })
                        ->leftJoin('player_tournament as apt', function ($join) {
                            $join->on('apt.player_id', '=', 'm.home_player_id')
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
                        ->sortByDesc('assists') as $assister)
                    <tr>
                        <td>{{ $assister->name }}</td>
                        <td>{{ $assister->team }}</td>
                        <td>{{ $assister->assists }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
</div>

<div class="box-footer">
    <a href="{{ route('tournament.index') }}"
       class="btn btn-default">
        Voltar
    </a>
    <!--button type="submit" class="btn btn-success pull-right">
        Ativar torneio
    </button-->
</div>

@push('js')
<script>
    $(document).ready(function() {
        $('.start-match, .end-match').click(function() {
            $(this).closest('form').submit();
        });

        $('.start-match-form').on('submit', function() {
            return confirm('Deseja realmente iniciar a partida?');
        });

        $('.end-match-form').on('submit', function() {
            return confirm('Deseja realmente terminar a partida?');
        });
    });
</script>
@endpush