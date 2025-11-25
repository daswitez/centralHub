<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="lote_planta_id" class="form-label">{{ __('Lote Planta Id') }}</label>
            <input type="text" name="lote_planta_id" class="form-control @error('lote_planta_id') is-invalid @enderror" value="{{ old('lote_planta_id', $planta.loteplantum?->lote_planta_id) }}" id="lote_planta_id" placeholder="Lote Planta Id">
            {!! $errors->first('lote_planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_lote_planta" class="form-label">{{ __('Codigo Lote Planta') }}</label>
            <input type="text" name="codigo_lote_planta" class="form-control @error('codigo_lote_planta') is-invalid @enderror" value="{{ old('codigo_lote_planta', $planta.loteplantum?->codigo_lote_planta) }}" id="codigo_lote_planta" placeholder="Codigo Lote Planta">
            {!! $errors->first('codigo_lote_planta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="planta_id" class="form-label">{{ __('Planta Id') }}</label>
            <input type="text" name="planta_id" class="form-control @error('planta_id') is-invalid @enderror" value="{{ old('planta_id', $planta.loteplantum?->planta_id) }}" id="planta_id" placeholder="Planta Id">
            {!! $errors->first('planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_inicio" class="form-label">{{ __('Fecha Inicio') }}</label>
            <input type="text" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio', $planta.loteplantum?->fecha_inicio) }}" id="fecha_inicio" placeholder="Fecha Inicio">
            {!! $errors->first('fecha_inicio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_fin" class="form-label">{{ __('Fecha Fin') }}</label>
            <input type="text" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" value="{{ old('fecha_fin', $planta.loteplantum?->fecha_fin) }}" id="fecha_fin" placeholder="Fecha Fin">
            {!! $errors->first('fecha_fin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="rendimiento_pct" class="form-label">{{ __('Rendimiento Pct') }}</label>
            <input type="text" name="rendimiento_pct" class="form-control @error('rendimiento_pct') is-invalid @enderror" value="{{ old('rendimiento_pct', $planta.loteplantum?->rendimiento_pct) }}" id="rendimiento_pct" placeholder="Rendimiento Pct">
            {!! $errors->first('rendimiento_pct', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>