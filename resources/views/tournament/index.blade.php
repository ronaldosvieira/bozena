@extends('adminlte::page')


@section('content_header')
    <h1>
        Torneios
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Torneios</li>
    </ol>
@stop

@section('content')
    <!-- general form elements -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de torneios criados</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            @include('partials.session_message')

            @if ($tournaments->count())
            <table id="tabela" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Jogadores</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tournaments as $tournament)
                    <tr>
                        <td>{{ $tournament->name }}</td>
                        <td>
                            {{ $tournament->players->reduce(function($carry, $player) {
                                return $carry . $player->name . " ";
                            }) }}
                        </td>
                        <td>
                            <a href="{{ route('tournament.edit', $tournament->id) }}">
                                <i class="material-icons md-black md-24 tooltip-edit">
                                    &#xE150;
                                </i>
                            </a>
                            {{-- Form::open(['method' => 'delete',
                                'route' => ['usuario.destroy', $usuario->id],
                                'class' => 'form-inline-destroy', 'data-nome' => $usuario->nome]) }}
                                <a href="#" class="destroy">
                                    <i class="material-icons md-black md-24 tooltip-destroy">
                                        &#xE872;
                                    </i>
                                </a>
                            {{ Form::close() --}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p>
                    Nenhum torneio cadastrado.
                    <a href="{{ route('tournament.create') }}">Crie um!</a>
                </p>
            @endif
        </div>

    </div>
    <!-- /.box -->
@stop