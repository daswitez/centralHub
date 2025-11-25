<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="lote_planta_id" class="form-label">{{ __('Lote Planta Id') }}</label>
            <input type="text" name="lote_planta_id" class="form-control @error('lote_planta_id') is-invalid @enderror" value="{{ old('lote_planta_id', $planta.loteplantaEntradacampo?->lote_planta_id) }}" id="lote_planta_id" placeholder="Lote Planta Id">
            {!! $errors->first('lote_planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_campo_id" class="form-label">{{ __('Lote Campo Id') }}</label>
            <input type="text" name="lote_campo_id" class="form-control @error('lote_campo_id') is-invalid @enderror" value="{{ old('lote_campo_id', $planta.loteplantaEntradacampo?->lote_campo_id) }}" id="lote_campo_id" placeholder="Lote Campo Id">
            {!! $errors->first('lote_campo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="peso_entrada_t" class="form-label">{{ __('Peso Entrada T') }}</label>
            <input type="text" name="peso_entrada_t" class="form-control @error('peso_entrada_t') is-invalid @enderror" value="{{ old('peso_entrada_t', $planta.loteplantaEntradacampo?->peso_entrada_t) }}" id="peso_entrada_t" placeholder="Peso Entrada T">
            {!! $errors->first('peso_entrada_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>