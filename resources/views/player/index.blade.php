@extends('adminlte::page')


@section('content_header')
    <h1>
        Jogadores
    </h1>
    <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Jogadores</li>
    </ol>
@stop

@section('content')
    <!-- general form elements -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de jogadores existentes</h3>

            <div class="pull-right">
                <a href="{{ route('player.create') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus">&nbsp;</i>
                    <span>Adicionar novo jogador</span>
                </a>
            </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            @include('partials.session_message')

            @if ($players->count())
            <table id="tabela" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($players as $player)
                    <tr>
                        <td>{{ $player->name }}</td>
                        <td>
                            {{ $player->user? $player->user->name : '-' }}
                        </td>
                        <td>
                            <a href="{{ route('player.edit', $player->id) }}" class="acao">
                                <i class="fa fa-pencil" data-toggle="tooltip"
                                   data-placement="bottom" title="Editar"></i>
                            </a>
                            {!! Form::open(['method' => 'delete',
                                'route' => ['player.destroy', $player->id],
                                'class' => 'form-destroy', 'data-nome' => $player->name]) !!}
                                <a class="acao destroy" data-toggle="tooltip"
                                   data-placement="bottom" title="Excluir">
                                    <i class="fa fa-trash"></i>
                                </a>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p>
                    Nenhum jogador cadastrado.
                    <a href="{{ route('player.create') }}">Adicione um!</a>
                </p>
            @endif
        </div>

    </div>
    <!-- /.box -->
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('.destroy').on('click', function() {
                $(this).closest('form').submit();
            });

            $('.form-destroy').on("submit", function () {
                if ($(this).find('.destroy').data('nome')) {
                    return confirm("Tem certeza que deseja excluir " +
                        $(this).find('.destroy').data('nome') + "?");
                } else {
                    return confirm("Tem certeza que deseja excluir?");
                }
            });
        });
    </script>
@endpush