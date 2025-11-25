<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="envio_id" class="form-label">{{ __('Envio Id') }}</label>
            <input type="text" name="envio_id" class="form-control @error('envio_id') is-invalid @enderror" value="{{ old('envio_id', $envio?->envio_id) }}" id="envio_id" placeholder="Envio Id">
            {!! $errors->first('envio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_envio" class="form-label">{{ __('Codigo Envio') }}</label>
            <input type="text" name="codigo_envio" class="form-control @error('codigo_envio') is-invalid @enderror" value="{{ old('codigo_envio', $envio?->codigo_envio) }}" id="codigo_envio" placeholder="Codigo Envio">
            {!! $errors->first('codigo_envio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ruta_id" class="form-label">{{ __('Ruta Id') }}</label>
            <input type="text" name="ruta_id" class="form-control @error('ruta_id') is-invalid @enderror" value="{{ old('ruta_id', $envio?->ruta_id) }}" id="ruta_id" placeholder="Ruta Id">
            {!! $errors->first('ruta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="transportista_id" class="form-label">{{ __('Transportista Id') }}</label>
            <input type="text" name="transportista_id" class="form-control @error('transportista_id') is-invalid @enderror" value="{{ old('transportista_id', $envio?->transportista_id) }}" id="transportista_id" placeholder="Transportista Id">
            {!! $errors->first('transportista_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_salida" class="form-label">{{ __('Fecha Salida') }}</label>
            <input type="text" name="fecha_salida" class="form-control @error('fecha_salida') is-invalid @enderror" value="{{ old('fecha_salida', $envio?->fecha_salida) }}" id="fecha_salida" placeholder="Fecha Salida">
            {!! $errors->first('fecha_salida', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_llegada" class="form-label">{{ __('Fecha Llegada') }}</label>
            <input type="text" name="fecha_llegada" class="form-control @error('fecha_llegada') is-invalid @enderror" value="{{ old('fecha_llegada', $envio?->fecha_llegada) }}" id="fecha_llegada" placeholder="Fecha Llegada">
            {!! $errors->first('fecha_llegada', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="temp_min_c" class="form-label">{{ __('Temp Min C') }}</label>
            <input type="text" name="temp_min_c" class="form-control @error('temp_min_c') is-invalid @enderror" value="{{ old('temp_min_c', $envio?->temp_min_c) }}" id="temp_min_c" placeholder="Temp Min C">
            {!! $errors->first('temp_min_c', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="temp_max_c" class="form-label">{{ __('Temp Max C') }}</label>
            <input type="text" name="temp_max_c" class="form-control @error('temp_max_c') is-invalid @enderror" value="{{ old('temp_max_c', $envio?->temp_max_c) }}" id="temp_max_c" placeholder="Temp Max C">
            {!! $errors->first('temp_max_c', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $envio?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="almacen_origen_id" class="form-label">{{ __('Almacen Origen Id') }}</label>
            <input type="text" name="almacen_origen_id" class="form-control @error('almacen_origen_id') is-invalid @enderror" value="{{ old('almacen_origen_id', $envio?->almacen_origen_id) }}" id="almacen_origen_id" placeholder="Almacen Origen Id">
            {!! $errors->first('almacen_origen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>