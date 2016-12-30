{!! Form::open([
    'route' => ['tournament.activate', $tournament->id],
    'method' => 'post']) !!}

@foreach ($tournament->players as $player)
    <div class="col-sm-6 col-md-3">
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
                    {!! Form::label('team[]', 'Time:', ['class' => 'control-label']) !!}
                    {!! Form::text('team[' . $player->id . ']',
                        $player->pivot->team,
                        ['class' => 'form-control', 'required']) !!}
                </h5>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
@endforeach

<div class="box-footer">
    <a href="{{ route('tournament.index') }}"
       class="btn btn-default">
        Voltar
    </a>
    <button type="submit" class="btn btn-success pull-right">
        Ativar torneio
    </button>
</div>

{!! Form::close() !!}