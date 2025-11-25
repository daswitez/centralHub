<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="control_id" class="form-label">{{ __('Control Id') }}</label>
            <input type="text" name="control_id" class="form-control @error('control_id') is-invalid @enderror" value="{{ old('control_id', $controlproceso?->control_id) }}" id="control_id" placeholder="Control Id">
            {!! $errors->first('control_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_planta_id" class="form-label">{{ __('Lote Planta Id') }}</label>
            <input type="text" name="lote_planta_id" class="form-control @error('lote_planta_id') is-invalid @enderror" value="{{ old('lote_planta_id', $controlproceso?->lote_planta_id) }}" id="lote_planta_id" placeholder="Lote Planta Id">
            {!! $errors->first('lote_planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="etapa" class="form-label">{{ __('Etapa') }}</label>
            <input type="text" name="etapa" class="form-control @error('etapa') is-invalid @enderror" value="{{ old('etapa', $controlproceso?->etapa) }}" id="etapa" placeholder="Etapa">
            {!! $errors->first('etapa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_hora" class="form-label">{{ __('Fecha Hora') }}</label>
            <input type="text" name="fecha_hora" class="form-control @error('fecha_hora') is-invalid @enderror" value="{{ old('fecha_hora', $controlproceso?->fecha_hora) }}" id="fecha_hora" placeholder="Fecha Hora">
            {!! $errors->first('fecha_hora', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="parametro" class="form-label">{{ __('Parametro') }}</label>
            <input type="text" name="parametro" class="form-control @error('parametro') is-invalid @enderror" value="{{ old('parametro', $controlproceso?->parametro) }}" id="parametro" placeholder="Parametro">
            {!! $errors->first('parametro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_num" class="form-label">{{ __('Valor Num') }}</label>
            <input type="text" name="valor_num" class="form-control @error('valor_num') is-invalid @enderror" value="{{ old('valor_num', $controlproceso?->valor_num) }}" id="valor_num" placeholder="Valor Num">
            {!! $errors->first('valor_num', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor_texto" class="form-label">{{ __('Valor Texto') }}</label>
            <input type="text" name="valor_texto" class="form-control @error('valor_texto') is-invalid @enderror" value="{{ old('valor_texto', $controlproceso?->valor_texto) }}" id="valor_texto" placeholder="Valor Texto">
            {!! $errors->first('valor_texto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $controlproceso?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>