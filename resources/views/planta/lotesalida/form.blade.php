<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="lote_salida_id" class="form-label">{{ __('Lote Salida Id') }}</label>
            <input type="text" name="lote_salida_id" class="form-control @error('lote_salida_id') is-invalid @enderror" value="{{ old('lote_salida_id', $lotesalida?->lote_salida_id) }}" id="lote_salida_id" placeholder="Lote Salida Id">
            {!! $errors->first('lote_salida_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_lote_salida" class="form-label">{{ __('Codigo Lote Salida') }}</label>
            <input type="text" name="codigo_lote_salida" class="form-control @error('codigo_lote_salida') is-invalid @enderror" value="{{ old('codigo_lote_salida', $lotesalida?->codigo_lote_salida) }}" id="codigo_lote_salida" placeholder="Codigo Lote Salida">
            {!! $errors->first('codigo_lote_salida', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_planta_id" class="form-label">{{ __('Lote Planta Id') }}</label>
            <input type="text" name="lote_planta_id" class="form-control @error('lote_planta_id') is-invalid @enderror" value="{{ old('lote_planta_id', $lotesalida?->lote_planta_id) }}" id="lote_planta_id" placeholder="Lote Planta Id">
            {!! $errors->first('lote_planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sku" class="form-label">{{ __('Sku') }}</label>
            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $lotesalida?->sku) }}" id="sku" placeholder="Sku">
            {!! $errors->first('sku', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="peso_t" class="form-label">{{ __('Peso T') }}</label>
            <input type="text" name="peso_t" class="form-control @error('peso_t') is-invalid @enderror" value="{{ old('peso_t', $lotesalida?->peso_t) }}" id="peso_t" placeholder="Peso T">
            {!! $errors->first('peso_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_empaque" class="form-label">{{ __('Fecha Empaque') }}</label>
            <input type="text" name="fecha_empaque" class="form-control @error('fecha_empaque') is-invalid @enderror" value="{{ old('fecha_empaque', $lotesalida?->fecha_empaque) }}" id="fecha_empaque" placeholder="Fecha Empaque">
            {!! $errors->first('fecha_empaque', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>