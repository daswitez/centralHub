<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="lectura_id" class="form-label">{{ __('Lectura Id') }}</label>
            <input type="text" name="lectura_id" class="form-control @error('lectura_id') is-invalid @enderror" value="{{ old('lectura_id', $sensorlectura?->lectura_id) }}" id="lectura_id" placeholder="Lectura Id">
            {!! $errors->first('lectura_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_campo_id" class="form-label">{{ __('Lote Campo Id') }}</label>
            <input type="text" name="lote_campo_id" class="form-control @error('lote_campo_id') is-invalid @enderror" value="{{ old('lote_campo_id', $sensorlectura?->lote_campo_id) }}" id="lote_campo_id" placeholder="Lote Campo Id">
            {!! $errors->first('lote_campo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_hora" class="form-label">{{ __('Fecha Hora') }}</label>
            <input type="text" name="fecha_hora" class="form-control @error('fecha_hora') is-invalid @enderror" value="{{ old('fecha_hora', $sensorlectura?->fecha_hora) }}" id="fecha_hora" placeholder="Fecha Hora">
            {!! $errors->first('fecha_hora', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $sensorlectura?->tipo) }}" id="tipo" placeholder="Tipo">
            {!! $errors->first('tipo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_num" class="form-label">{{ __('Valor Num') }}</label>
            <input type="text" name="valor_num" class="form-control @error('valor_num') is-invalid @enderror" value="{{ old('valor_num', $sensorlectura?->valor_num) }}" id="valor_num" placeholder="Valor Num">
            {!! $errors->first('valor_num', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_texto" class="form-label">{{ __('Valor Texto') }}</label>
            <input type="text" name="valor_texto" class="form-control @error('valor_texto') is-invalid @enderror" value="{{ old('valor_texto', $sensorlectura?->valor_texto) }}" id="valor_texto" placeholder="Valor Texto">
            {!! $errors->first('valor_texto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>