{{-- resources/views/tournament/edit.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>Torneios</h1>

    @include('partials.breadcrumb', ['itens' => [
        ['nome' => 'Torneios', 'route' => 'tournament.index'],
        ['nome' => 'Editar', 'disabled' => true]
        ]])
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Editar torneio</h3>
        </div>

        <div class="box-body">
            @include('partials.session_message')

            {{ Form::model($unidade,
                ['route' => ['tournament.update', $tournament->id],
                'method' => 'patch',
                'class' => 'form-horizontal']) }}

            @include('tournament/partials/_form',
                ['texto_botao' => 'Salvar', 'edit' => true])

            {{ Form::close() }}
        </div>

    </div>
@stop