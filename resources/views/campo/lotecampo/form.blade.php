<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="lote_campo_id" class="form-label">{{ __('Lote Campo Id') }}</label>
            <input type="text" name="lote_campo_id" class="form-control @error('lote_campo_id') is-invalid @enderror" value="{{ old('lote_campo_id', $lotecampo?->lote_campo_id) }}" id="lote_campo_id" placeholder="Lote Campo Id">
            {!! $errors->first('lote_campo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_lote_campo" class="form-label">{{ __('Codigo Lote Campo') }}</label>
            <input type="text" name="codigo_lote_campo" class="form-control @error('codigo_lote_campo') is-invalid @enderror" value="{{ old('codigo_lote_campo', $lotecampo?->codigo_lote_campo) }}" id="codigo_lote_campo" placeholder="Codigo Lote Campo">
            {!! $errors->first('codigo_lote_campo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="productor_id" class="form-label">{{ __('Productor Id') }}</label>
            <input type="text" name="productor_id" class="form-control @error('productor_id') is-invalid @enderror" value="{{ old('productor_id', $lotecampo?->productor_id) }}" id="productor_id" placeholder="Productor Id">
            {!! $errors->first('productor_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="variedad_id" class="form-label">{{ __('Variedad Id') }}</label>
            <input type="text" name="variedad_id" class="form-control @error('variedad_id') is-invalid @enderror" value="{{ old('variedad_id', $lotecampo?->variedad_id) }}" id="variedad_id" placeholder="Variedad Id">
            {!! $errors->first('variedad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="superficie_ha" class="form-label">{{ __('Superficie Ha') }}</label>
            <input type="text" name="superficie_ha" class="form-control @error('superficie_ha') is-invalid @enderror" value="{{ old('superficie_ha', $lotecampo?->superficie_ha) }}" id="superficie_ha" placeholder="Superficie Ha">
            {!! $errors->first('superficie_ha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_siembra" class="form-label">{{ __('Fecha Siembra') }}</label>
            <input type="text" name="fecha_siembra" class="form-control @error('fecha_siembra') is-invalid @enderror" value="{{ old('fecha_siembra', $lotecampo?->fecha_siembra) }}" id="fecha_siembra" placeholder="Fecha Siembra">
            {!! $errors->first('fecha_siembra', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_cosecha" class="form-label">{{ __('Fecha Cosecha') }}</label>
            <input type="text" name="fecha_cosecha" class="form-control @error('fecha_cosecha') is-invalid @enderror" value="{{ old('fecha_cosecha', $lotecampo?->fecha_cosecha) }}" id="fecha_cosecha" placeholder="Fecha Cosecha">
            {!! $errors->first('fecha_cosecha', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="humedad_suelo_pct" class="form-label">{{ __('Humedad Suelo Pct') }}</label>
            <input type="text" name="humedad_suelo_pct" class="form-control @error('humedad_suelo_pct') is-invalid @enderror" value="{{ old('humedad_suelo_pct', $lotecampo?->humedad_suelo_pct) }}" id="humedad_suelo_pct" placeholder="Humedad Suelo Pct">
            {!! $errors->first('humedad_suelo_pct', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>