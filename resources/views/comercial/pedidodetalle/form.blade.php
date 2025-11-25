<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="pedido_detalle_id" class="form-label">{{ __('Pedido Detalle Id') }}</label>
            <input type="text" name="pedido_detalle_id" class="form-control @error('pedido_detalle_id') is-invalid @enderror" value="{{ old('pedido_detalle_id', $pedidodetalle?->pedido_detalle_id) }}" id="pedido_detalle_id" placeholder="Pedido Detalle Id">
            {!! $errors->first('pedido_detalle_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="pedido_id" class="form-label">{{ __('Pedido Id') }}</label>
            <input type="text" name="pedido_id" class="form-control @error('pedido_id') is-invalid @enderror" value="{{ old('pedido_id', $pedidodetalle?->pedido_id) }}" id="pedido_id" placeholder="Pedido Id">
            {!! $errors->first('pedido_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sku" class="form-label">{{ __('Sku') }}</label>
            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $pedidodetalle?->sku) }}" id="sku" placeholder="Sku">
            {!! $errors->first('sku', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_t" class="form-label">{{ __('Cantidad T') }}</label>
            <input type="text" name="cantidad_t" class="form-control @error('cantidad_t') is-invalid @enderror" value="{{ old('cantidad_t', $pedidodetalle?->cantidad_t) }}" id="cantidad_t" placeholder="Cantidad T">
            {!! $errors->first('cantidad_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="precio_unit_usd" class="form-label">{{ __('Precio Unit Usd') }}</label>
            <input type="text" name="precio_unit_usd" class="form-control @error('precio_unit_usd') is-invalid @enderror" value="{{ old('precio_unit_usd', $pedidodetalle?->precio_unit_usd) }}" id="precio_unit_usd" placeholder="Precio Unit Usd">
            {!! $errors->first('precio_unit_usd', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>