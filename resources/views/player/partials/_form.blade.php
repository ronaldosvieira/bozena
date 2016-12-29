<div class="form-group @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Nome *',
        ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('name', old('name'),
            ['class' => 'form-control', 'required']) !!}
    </div>
    @if ($errors->has('name'))
        <div class="col-sm-offset-2 col-sm-9">
            <p class="help-block">{{ $errors->first('name') }}</p>
        </div>
    @endif
</div>

@include('partials.mensagem_campos_obrigatorios')

<div class="box-footer">
    <a href="{{ route('player.index') }}">
        <button type="button" class="btn btn-default">
            Cancelar
        </button>
    </a>
    <button type="submit" class="btn btn-success pull-right">
        {{ $texto_botao }}
    </button>
</div>