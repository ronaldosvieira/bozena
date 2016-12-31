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
                    <tr class="match" data-id="{{ $match->id }}">
                        <td class="col-xs-2 match-state">
                            {{ $match->state->name }}
                        </td>
                        <td class="col-xs-3 home-team text-right">
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
                        <td class="col-xs-3 away-team text-left">
                            {{ $match->awayPlayer->name }}
                        </td>
                        <td class="col-xs-2">
                            @if ($match->state->can_add_goals)
                                <a class="acao add-goal" data-toggle="modal" data-target="#goal-modal-{{ $match->id }}"
                                    data-id="{{ $match->id }}">
                                    <i class="fa fa-soccer-ball-o" data-toggle="tooltip"
                                       data-placement="bottom" title="Adicionar gol"></i>
                                </a>

                                {!! Form::open([
                                    'method' => 'post',
                                    'route' => ['tournament.match.end', $match->tournament_id, $match->id],
                                    'class' => 'end-match-form'])
                                !!}
                                <a class="acao end-match">
                                    <i class="fa fa-hand-paper-o" data-toggle="tooltip"
                                       data-placement="bottom" title="Terminar partida"></i>
                                </a>
                                {!! Form::close() !!}
                            @elseif (!$match->state->is_done)
                                {!! Form::open([
                                    'method' => 'post',
                                    'route' => ['tournament.match.start', $match->tournament_id, $match->id],
                                    'class' => 'start-match-form'])
                                !!}
                                <a class="acao start-match">
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

<!-- Modal -->
<div class="modal fade goal-modal" id="goal-modal-{{ $match->id }}" tabindex="-1" role="dialog" aria-labelledby="goal-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{ Form::open(['method' => 'post',
                    'route' => ['tournament.match.goal.store', $match->tournament_id, $match->id],
                    'class' => 'form-horizontal']) }}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="goal-modal-label">
                    Adicionar gol à partida
                    <span class="home-team-modal">{{ $match->homePlayer->name }}</span>
                    x
                    <span class="away-team-modal">{{ $match->awayPlayer->name }}</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group team-modal">
                    {!! Form::label('team', 'Jogador *', ['class' => 'control-label col-sm-3 text-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('team',
                            ['HOME' => $match->homePlayer->name, 'AWAY' => $match->awayPlayer->name],
                            null, ['class' => 'form-control', 'required', 'placeholder' => '(Escolha um jogador)']) !!}
                    </div>
                </div>

                <div class="form-group scorer-modal">
                    {!! Form::label('scorer', 'Autor do gol *', ['class' => 'control-label col-sm-3 text-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('scorer', null,
                            ['class' => 'form-control', 'required']) !!}
                    </div>
                </div>

                <div class="form-group assister-modal">
                    {!! Form::label('assister', 'Autor da assistência',
                        ['class' => 'control-label col-sm-3 text-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('assister', null,
                            ['class' => 'form-control']) !!}
                    </div>
                </div>

                <p class="pull-right">* Campos obrigatórios</p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success add-goal-submit">Adicionar gol</button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>