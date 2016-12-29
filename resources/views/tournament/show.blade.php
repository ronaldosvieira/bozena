{{-- resources/views/tournament/show.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>Torneios</h1>

    @include('partials.breadcrumb', ['itens' => [
        ['nome' => 'Torneios', 'route' => 'tournament.index'],
        ['nome' => $tournament->name, 'disabled' => true]
        ]])
@stop

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">
                Torneio {{ $tournament->name }}
            </h3>
        </div>

        <div class="box-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable col-sm-12 col-sm-offset-0 col-md-6 col-md-offset-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> Ação inválida</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="clearfix"></div>
            @endif

            @include('partials.session_message')

            {!! Form::open([
                'route' => ['tournament.activate', $tournament->id],
                'method' => 'post']) !!}

            @foreach ($tournament->players()->withPivot('team')->get() as $player)
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
                                old('team[' . $player->id . ']'),
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
        </div>

    </div>
@stop