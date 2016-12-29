{{-- resources/views/player/edit.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>Jogadores</h1>

    @include('partials.breadcrumb', ['itens' => [
        ['nome' => 'Jogadores', 'route' => 'player.index'],
        ['nome' => 'Editar', 'disabled' => true]
        ]])
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Editar jogador</h3>
        </div>

        <div class="box-body">
            @include('partials.session_message')

            {{ Form::model($player,
                ['route' => ['player.update', $player->id],
                'method' => 'patch',
                'class' => 'form-horizontal']) }}

            @include('player/partials/_form',
                ['texto_botao' => 'Salvar', 'edit' => true])

            {{ Form::close() }}
        </div>

    </div>
@stop