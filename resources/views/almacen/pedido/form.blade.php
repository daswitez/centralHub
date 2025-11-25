<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="pedido_almacen_id" class="form-label">{{ __('Pedido Almacen Id') }}</label>
            <input type="text" name="pedido_almacen_id" class="form-control @error('pedido_almacen_id') is-invalid @enderror" value="{{ old('pedido_almacen_id', $pedido?->pedido_almacen_id) }}" id="pedido_almacen_id" placeholder="Pedido Almacen Id">
            {!! $errors->first('pedido_almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_pedido" class="form-label">{{ __('Codigo Pedido') }}</label>
            <input type="text" name="codigo_pedido" class="form-control @error('codigo_pedido') is-invalid @enderror" value="{{ old('codigo_pedido', $pedido?->codigo_pedido) }}" id="codigo_pedido" placeholder="Codigo Pedido">
            {!! $errors->first('codigo_pedido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="almacen_id" class="form-label">{{ __('Almacen Id') }}</label>
            <input type="text" name="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" value="{{ old('almacen_id', $pedido?->almacen_id) }}" id="almacen_id" placeholder="Almacen Id">
            {!! $errors->first('almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_pedido" class="form-label">{{ __('Fecha Pedido') }}</label>
            <input type="text" name="fecha_pedido" class="form-control @error('fecha_pedido') is-invalid @enderror" value="{{ old('fecha_pedido', $pedido?->fecha_pedido) }}" id="fecha_pedido" placeholder="Fecha Pedido">
            {!! $errors->first('fecha_pedido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $pedido?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>