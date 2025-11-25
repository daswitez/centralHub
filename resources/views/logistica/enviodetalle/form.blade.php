<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="envio_detalle_id" class="form-label">{{ __('Envio Detalle Id') }}</label>
            <input type="text" name="envio_detalle_id" class="form-control @error('envio_detalle_id') is-invalid @enderror" value="{{ old('envio_detalle_id', $enviodetalle?->envio_detalle_id) }}" id="envio_detalle_id" placeholder="Envio Detalle Id">
            {!! $errors->first('envio_detalle_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="envio_id" class="form-label">{{ __('Envio Id') }}</label>
            <input type="text" name="envio_id" class="form-control @error('envio_id') is-invalid @enderror" value="{{ old('envio_id', $enviodetalle?->envio_id) }}" id="envio_id" placeholder="Envio Id">
            {!! $errors->first('envio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_salida_id" class="form-label">{{ __('Lote Salida Id') }}</label>
            <input type="text" name="lote_salida_id" class="form-control @error('lote_salida_id') is-invalid @enderror" value="{{ old('lote_salida_id', $enviodetalle?->lote_salida_id) }}" id="lote_salida_id" placeholder="Lote Salida Id">
            {!! $errors->first('lote_salida_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cliente_id" class="form-label">{{ __('Cliente Id') }}</label>
            <input type="text" name="cliente_id" class="form-control @error('cliente_id') is-invalid @enderror" value="{{ old('cliente_id', $enviodetalle?->cliente_id) }}" id="cliente_id" placeholder="Cliente Id">
            {!! $errors->first('cliente_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_t" class="form-label">{{ __('Cantidad T') }}</label>
            <input type="text" name="cantidad_t" class="form-control @error('cantidad_t') is-invalid @enderror" value="{{ old('cantidad_t', $enviodetalle?->cantidad_t) }}" id="cantidad_t" placeholder="Cantidad T">
            {!! $errors->first('cantidad_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>