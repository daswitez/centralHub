<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="recepcion_id" class="form-label">{{ __('Recepcion Id') }}</label>
            <input type="text" name="recepcion_id" class="form-control @error('recepcion_id') is-invalid @enderror" value="{{ old('recepcion_id', $recepcion?->recepcion_id) }}" id="recepcion_id" placeholder="Recepcion Id">
            {!! $errors->first('recepcion_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="envio_id" class="form-label">{{ __('Envio Id') }}</label>
            <input type="text" name="envio_id" class="form-control @error('envio_id') is-invalid @enderror" value="{{ old('envio_id', $recepcion?->envio_id) }}" id="envio_id" placeholder="Envio Id">
            {!! $errors->first('envio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="almacen_id" class="form-label">{{ __('Almacen Id') }}</label>
            <input type="text" name="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" value="{{ old('almacen_id', $recepcion?->almacen_id) }}" id="almacen_id" placeholder="Almacen Id">
            {!! $errors->first('almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_recepcion" class="form-label">{{ __('Fecha Recepcion') }}</label>
            <input type="text" name="fecha_recepcion" class="form-control @error('fecha_recepcion') is-invalid @enderror" value="{{ old('fecha_recepcion', $recepcion?->fecha_recepcion) }}" id="fecha_recepcion" placeholder="Fecha Recepcion">
            {!! $errors->first('fecha_recepcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observacion" class="form-label">{{ __('Observacion') }}</label>
            <input type="text" name="observacion" class="form-control @error('observacion') is-invalid @enderror" value="{{ old('observacion', $recepcion?->observacion) }}" id="observacion" placeholder="Observacion">
            {!! $errors->first('observacion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>