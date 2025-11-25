<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="certificado_id" class="form-label">{{ __('Certificado Id') }}</label>
            <input type="text" name="certificado_id" class="form-control @error('certificado_id') is-invalid @enderror" value="{{ old('certificado_id', $certificado?->certificado_id) }}" id="certificado_id" placeholder="Certificado Id">
            {!! $errors->first('certificado_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_certificado" class="form-label">{{ __('Codigo Certificado') }}</label>
            <input type="text" name="codigo_certificado" class="form-control @error('codigo_certificado') is-invalid @enderror" value="{{ old('codigo_certificado', $certificado?->codigo_certificado) }}" id="codigo_certificado" placeholder="Codigo Certificado">
            {!! $errors->first('codigo_certificado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ambito" class="form-label">{{ __('Ambito') }}</label>
            <input type="text" name="ambito" class="form-control @error('ambito') is-invalid @enderror" value="{{ old('ambito', $certificado?->ambito) }}" id="ambito" placeholder="Ambito">
            {!! $errors->first('ambito', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="area" class="form-label">{{ __('Area') }}</label>
            <input type="text" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ old('area', $certificado?->area) }}" id="area" placeholder="Area">
            {!! $errors->first('area', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vigente_desde" class="form-label">{{ __('Vigente Desde') }}</label>
            <input type="text" name="vigente_desde" class="form-control @error('vigente_desde') is-invalid @enderror" value="{{ old('vigente_desde', $certificado?->vigente_desde) }}" id="vigente_desde" placeholder="Vigente Desde">
            {!! $errors->first('vigente_desde', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vigente_hasta" class="form-label">{{ __('Vigente Hasta') }}</label>
            <input type="text" name="vigente_hasta" class="form-control @error('vigente_hasta') is-invalid @enderror" value="{{ old('vigente_hasta', $certificado?->vigente_hasta) }}" id="vigente_hasta" placeholder="Vigente Hasta">
            {!! $errors->first('vigente_hasta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="emisor" class="form-label">{{ __('Emisor') }}</label>
            <input type="text" name="emisor" class="form-control @error('emisor') is-invalid @enderror" value="{{ old('emisor', $certificado?->emisor) }}" id="emisor" placeholder="Emisor">
            {!! $errors->first('emisor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="url_archivo" class="form-label">{{ __('Url Archivo') }}</label>
            <input type="text" name="url_archivo" class="form-control @error('url_archivo') is-invalid @enderror" value="{{ old('url_archivo', $certificado?->url_archivo) }}" id="url_archivo" placeholder="Url Archivo">
            {!! $errors->first('url_archivo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>