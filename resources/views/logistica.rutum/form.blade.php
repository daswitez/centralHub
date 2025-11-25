<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="ruta_id" class="form-label">{{ __('Ruta Id') }}</label>
            <input type="text" name="ruta_id" class="form-control @error('ruta_id') is-invalid @enderror" value="{{ old('ruta_id', $logistica.rutum?->ruta_id) }}" id="ruta_id" placeholder="Ruta Id">
            {!! $errors->first('ruta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_ruta" class="form-label">{{ __('Codigo Ruta') }}</label>
            <input type="text" name="codigo_ruta" class="form-control @error('codigo_ruta') is-invalid @enderror" value="{{ old('codigo_ruta', $logistica.rutum?->codigo_ruta) }}" id="codigo_ruta" placeholder="Codigo Ruta">
            {!! $errors->first('codigo_ruta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $logistica.rutum?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>