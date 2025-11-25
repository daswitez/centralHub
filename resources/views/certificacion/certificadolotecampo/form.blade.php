<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="certificado_id" class="form-label">{{ __('Certificado Id') }}</label>
            <input type="text" name="certificado_id" class="form-control @error('certificado_id') is-invalid @enderror" value="{{ old('certificado_id', $certificadolotecampo?->certificado_id) }}" id="certificado_id" placeholder="Certificado Id">
            {!! $errors->first('certificado_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_campo_id" class="form-label">{{ __('Lote Campo Id') }}</label>
            <input type="text" name="lote_campo_id" class="form-control @error('lote_campo_id') is-invalid @enderror" value="{{ old('lote_campo_id', $certificadolotecampo?->lote_campo_id) }}" id="lote_campo_id" placeholder="Lote Campo Id">
            {!! $errors->first('lote_campo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>