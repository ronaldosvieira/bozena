{{-- resources/views/tournament/create.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>Torneios</h1>

    @include('partials.breadcrumb', ['itens' => [
        ['nome' => 'Torneios', 'route' => 'tournament.index'],
        ['nome' => 'Criar', 'disabled' => true]
        ]])
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Criar torneio</h3>
        </div>

        <div class="box-body">
            @include('partials.session_message')

            {{ Form::model(new App\Tournament,
                ['route' => ['tournament.store'],
                'class' => 'form-horizontal']) }}

            @include('tournament/partials/_form',
                ['texto_botao' => 'Salvar', 'edit' => false])

            {{ Form::close() }}
        </div>

    </div>
@stop