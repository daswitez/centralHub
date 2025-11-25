<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="ruta_id" class="form-label">{{ __('Ruta Id') }}</label>
            <input type="text" name="ruta_id" class="form-control @error('ruta_id') is-invalid @enderror" value="{{ old('ruta_id', $rutaspunto?->ruta_id) }}" id="ruta_id" placeholder="Ruta Id">
            {!! $errors->first('ruta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="orden" class="form-label">{{ __('Orden') }}</label>
            <input type="text" name="orden" class="form-control @error('orden') is-invalid @enderror" value="{{ old('orden', $rutaspunto?->orden) }}" id="orden" placeholder="Orden">
            {!! $errors->first('orden', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cliente_id" class="form-label">{{ __('Cliente Id') }}</label>
            <input type="text" name="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" value="{{ old('cliente_id', $rutaspunto?->cliente_id) }}" id="cliente_id" placeholder="Cliente Id">
            {!! $errors->first('cliente_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>