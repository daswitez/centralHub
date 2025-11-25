<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="envio_detalle_alm_id" class="form-label">{{ __('Envio Detalle Alm Id') }}</label>
            <input type="text" name="envio_detalle_alm_id" class="form-control @error('envio_detalle_alm_id') is-invalid @enderror" value="{{ old('envio_detalle_alm_id', $enviodetallealmacen?->envio_detalle_alm_id) }}" id="envio_detalle_alm_id" placeholder="Envio Detalle Alm Id">
            {!! $errors->first('envio_detalle_alm_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="envio_id" class="form-label">{{ __('Envio Id') }}</label>
            <input type="text" name="envio_id" class="form-control @error('envio_id') is-invalid @enderror" value="{{ old('envio_id', $enviodetallealmacen?->envio_id) }}" id="envio_id" placeholder="Envio Id">
            {!! $errors->first('envio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_salida_id" class="form-label">{{ __('Lote Salida Id') }}</label>
            <input type="text" name="lote_salida_id" class="form-control @error('lote_salida_id') is-invalid @enderror" value="{{ old('lote_salida_id', $enviodetallealmacen?->lote_salida_id) }}" id="lote_salida_id" placeholder="Lote Salida Id">
            {!! $errors->first('lote_salida_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="almacen_id" class="form-label">{{ __('Almacen Id') }}</label>
            <input type="text" name="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" value="{{ old('almacen_id', $enviodetallealmacen?->almacen_id) }}" id="almacen_id" placeholder="Almacen Id">
            {!! $errors->first('almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_t" class="form-label">{{ __('Cantidad T') }}</label>
            <input type="text" name="cantidad_t" class="form-control @error('cantidad_t') is-invalid @enderror" value="{{ old('cantidad_t', $enviodetallealmacen?->cantidad_t) }}" id="cantidad_t" placeholder="Cantidad T">
            {!! $errors->first('cantidad_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>