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

            @if ($tournament->isActive())
                <div class="btn btn-xs refresh-button pull-right"
                    data-toggle="tooltip" title="Atualizar"
                    data-placement="bottom">
                    <i class="fa fa-fw fa-refresh"></i>
                    Atualizado às <span class="last"></span>
                </div>
            @endif
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

            @if ($tournament->isActive())
                @include('tournament.partials._manage')
            @else
                @include('tournament.partials._activate')
            @endif
        </div>

    </div>
@stop