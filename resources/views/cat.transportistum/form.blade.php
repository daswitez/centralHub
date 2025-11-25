<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="transportista_id" class="form-label">{{ __('Transportista Id') }}</label>
            <input type="text" name="transportista_id" class="form-control @error('transportista_id') is-invalid @enderror" value="{{ old('transportista_id', $cat.transportistum?->transportista_id) }}" id="transportista_id" placeholder="Transportista Id">
            {!! $errors->first('transportista_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_transp" class="form-label">{{ __('Codigo Transp') }}</label>
            <input type="text" name="codigo_transp" class="form-control @error('codigo_transp') is-invalid @enderror" value="{{ old('codigo_transp', $cat.transportistum?->codigo_transp) }}" id="codigo_transp" placeholder="Codigo Transp">
            {!! $errors->first('codigo_transp', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cat.transportistum?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nro_licencia" class="form-label">{{ __('Nro Licencia') }}</label>
            <input type="text" name="nro_licencia" class="form-control @error('nro_licencia') is-invalid @enderror" value="{{ old('nro_licencia', $cat.transportistum?->nro_licencia) }}" id="nro_licencia" placeholder="Nro Licencia">
            {!! $errors->first('nro_licencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>