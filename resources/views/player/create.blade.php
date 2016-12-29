{{-- resources/views/player/create.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>Jogadores</h1>

    @include('partials.breadcrumb', ['itens' => [
        ['nome' => 'Jogadores', 'route' => 'player.index'],
        ['nome' => 'Criar', 'disabled' => true]
        ]])
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Criar jogador</h3>
        </div>

        <div class="box-body">
            @include('partials.session_message')

            {{ Form::model(new App\Player,
                ['route' => ['player.store'],
                'class' => 'form-horizontal']) }}

            @include('player/partials/_form',
                ['texto_botao' => 'Salvar', 'edit' => false])

            {{ Form::close() }}
        </div>

    </div>
@stop