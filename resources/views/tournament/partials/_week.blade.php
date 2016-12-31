<div class="week col-xs-12 col-lg-6">
    <div class="row">
        <h4 class="col-lg-offset-4 col-lg-4 text-center">
            {{ $key }}ª rodada
        </h4>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped table-condensed no-margin">
                @foreach ($matches->sortBy('id') as $match)
                    <tr class="match">
                        <td class="col-xs-2 match-state">
                            {{ $match->state->name }}
                        </td>
                        <td class="col-xs-3 text-right">
                            {{ $match->homePlayer->name }}
                        </td>
                        <td class="col-xs-1 text-center">
                            <span class="score score-home">
                                @if ($match->isStarted())
                                    {{ $match->goals->where('team', 'HOME')->count() }}
                                @endif
                            </span>
                            x
                            <span class="score score-away">
                                @if ($match->isStarted())
                                    {{ $match->goals->where('team', 'AWAY')->count() }}
                                @endif
                            </span>
                        </td>
                        <td class="col-xs-3 text-left">
                            {{ $match->awayPlayer->name }}
                        </td>
                        <td class="col-xs-2">
                            @if ($match->state->can_add_goals)
                                <a class="acao">
                                    <i class="fa fa-soccer-ball-o" data-toggle="tooltip"
                                       data-placement="bottom" title="Adicionar gol"></i>
                                </a>

                                {!! Form::open([
                                    'method' => 'post',
                                    'route' => ['tournament.match.end', $match->tournament_id, $match->id],
                                    'class' => 'terminar-partida-form'])
                                !!}
                                <a class="acao terminar-partida">
                                    <i class="fa fa-hand-paper-o" data-toggle="tooltip"
                                       data-placement="bottom" title="Terminar partida"></i>
                                </a>
                                {!! Form::close() !!}
                            @elseif (!$match->state->is_done)
                                {!! Form::open([
                                    'method' => 'post',
                                    'route' => ['tournament.match.start', $match->tournament_id, $match->id],
                                    'class' => 'iniciar-partida-form'])
                                !!}
                                <a class="acao iniciar-partida">
                                    <i class="fa fa-play" data-toggle="tooltip"
                                       data-placement="bottom" title="Iniciar partida"></i>
                                </a>
                                {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

