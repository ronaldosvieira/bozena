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

            <div class="pull-right">
                <a href="{{ route('tournament.create') }}" class="btn btn-sm btn-success">
                    <i class="fa fa-plus">&nbsp;</i>
                    <span>Criar novo torneio</span>
                </a>
            </div>
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
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tournaments as $tournament)
                    <tr>
                        <td>
                            <a href="{{ route('tournament.show', $tournament->id) }}">
                                {{ $tournament->name }}
                            </a>
                        </td>
                        <td>
                            {{ join(", ", $tournament->players->map(function($player) {
                                return $player->name;
                            })->toArray()) }}
                        </td>
                        <td>{{ $tournament->state->name }}</td>
                        <td>
                            <a href="{{ route('tournament.show', $tournament->id) }}" class="acao">
                                <i class="fa fa-eye" data-toggle="tooltip"
                                   data-placement="bottom" title="Ver informações"></i>
                            </a>
                            @if ($tournament->state->can_edit)
                                <a href="{{ route('tournament.edit', $tournament->id) }}" class="acao">
                                    <i class="fa fa-pencil" data-toggle="tooltip"
                                       data-placement="bottom" title="Editar"></i>
                                </a>
                            @endif
                            @if ($tournament->state->can_remove)
                                {{--!! Form::open(['method' => 'delete',
                                    'route' => ['tournament.destroy', $tournament->id],
                                    'class' => 'form-destroy', 'data-nome' => $tournament->name]) !!}
                                <a class="acao destroy" data-toggle="tooltip"
                                   data-placement="bottom" title="Excluir">
                                    <i class="fa fa-trash"></i>
                                </a>
                                {!! Form::close() !!--}}
                            @endif
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