<div class="form-group @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nome *',
        ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('name', old('name'),
            ['class' => 'form-control', 'required']) !!}
    </div>
    @if ($errors->has('name'))
        <p class="help-block">{{ $errors->first('name') }}</p>
    @endif
</div>

<div class="form-group @if($errors->has('player_id[]')) has-error @endif">
    {!! Form::label('player_id[]', 'Jogadores *',
        ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::select('player_id[]', $players->sortBy('nome')->pluck('name', 'id'),
            old('player_id[]'), [
            'class' => 'form-control select2',
            'multiple', 'data-placeholder' => 'Selecione os jogadores',
        ]) !!}
        @if ($errors->has('player_id[]'))
            <p class="help-block">{{ $errors->first('player_id[]') }}</p>
        @endif
    </div>
</div>

@include('partials.mensagem_campos_obrigatorios')

<div class="box-footer">
    <a href="{{ route('tournament.index') }}">
        <button type="button" class="btn btn-default">
            Cancelar
        </button>
    </a>
    <button type="submit" class="btn btn-success pull-right">
        {{ $texto_botao }}
    </button>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('vendor/select2/js/select2.js') }}"></script>
<script src="{{ asset('vendor/select2/js/i18n/pt-BR.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush