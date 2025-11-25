<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="mov_id" class="form-label">{{ __('Mov Id') }}</label>
            <input type="text" name="mov_id" class="form-control @error('mov_id') is-invalid @enderror" value="{{ old('mov_id', $movimiento?->mov_id) }}" id="mov_id" placeholder="Mov Id">
            {!! $errors->first('mov_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="almacen_id" class="form-label">{{ __('Almacen Id') }}</label>
            <input type="text" name="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" value="{{ old('almacen_id', $movimiento?->almacen_id) }}" id="almacen_id" placeholder="Almacen Id">
            {!! $errors->first('almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lote_salida_id" class="form-label">{{ __('Lote Salida Id') }}</label>
            <input type="text" name="lote_salida_id" class="form-control @error('lote_salida_id') is-invalid @enderror" value="{{ old('lote_salida_id', $movimiento?->lote_salida_id) }}" id="lote_salida_id" placeholder="Lote Salida Id">
            {!! $errors->first('lote_salida_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $movimiento?->tipo) }}" id="tipo" placeholder="Tipo">
            {!! $errors->first('tipo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad_t" class="form-label">{{ __('Cantidad T') }}</label>
            <input type="text" name="cantidad_t" class="form-control @error('cantidad_t') is-invalid @enderror" value="{{ old('cantidad_t', $movimiento?->cantidad_t) }}" id="cantidad_t" placeholder="Cantidad T">
            {!! $errors->first('cantidad_t', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_mov" class="form-label">{{ __('Fecha Mov') }}</label>
            <input type="text" name="fecha_mov" class="form-control @error('fecha_mov') is-invalid @enderror" value="{{ old('fecha_mov', $movimiento?->fecha_mov) }}" id="fecha_mov" placeholder="Fecha Mov">
            {!! $errors->first('fecha_mov', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="referencia" class="form-label">{{ __('Referencia') }}</label>
            <input type="text" name="referencia" class="form-control @error('referencia') is-invalid @enderror" value="{{ old('referencia', $movimiento?->referencia) }}" id="referencia" placeholder="Referencia">
            {!! $errors->first('referencia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="detalle" class="form-label">{{ __('Detalle') }}</label>
            <input type="text" name="detalle" class="form-control @error('detalle') is-invalid @enderror" value="{{ old('detalle', $movimiento?->detalle) }}" id="detalle" placeholder="Detalle">
            {!! $errors->first('detalle', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>