<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="certificado_padre_id" class="form-label">{{ __('Certificado Padre Id') }}</label>
            <input type="text" name="certificado_padre_id" class="form-control @error('certificado_padre_id') is-invalid @enderror" value="{{ old('certificado_padre_id', $certificadocadena?->certificado_padre_id) }}" id="certificado_padre_id" placeholder="Certificado Padre Id">
            {!! $errors->first('certificado_padre_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="certificado_hijo_id" class="form-label">{{ __('Certificado Hijo Id') }}</label>
            <input type="text" name="certificado_hijo_id" class="form-control @error('certificado_hijo_id') is-invalid @enderror" value="{{ old('certificado_hijo_id', $certificadocadena?->certificado_hijo_id) }}" id="certificado_hijo_id" placeholder="Certificado Hijo Id">
            {!! $errors->first('certificado_hijo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>