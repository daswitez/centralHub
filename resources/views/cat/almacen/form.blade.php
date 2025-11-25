<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="almacen_id" class="form-label">{{ __('Almacen Id') }}</label>
            <input type="text" name="almacen_id" class="form-control @error('almacen_id') is-invalid @enderror" value="{{ old('almacen_id', $almacen?->almacen_id) }}" id="almacen_id" placeholder="Almacen Id">
            {!! $errors->first('almacen_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_almacen" class="form-label">{{ __('Codigo Almacen') }}</label>
            <input type="text" name="codigo_almacen" class="form-control @error('codigo_almacen') is-invalid @enderror" value="{{ old('codigo_almacen', $almacen?->codigo_almacen) }}" id="codigo_almacen" placeholder="Codigo Almacen">
            {!! $errors->first('codigo_almacen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $almacen?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="municipio_id" class="form-label">{{ __('Municipio Id') }}</label>
            <input type="text" name="municipio_id" class="form-control @error('municipio_id') is-invalid @enderror" value="{{ old('municipio_id', $almacen?->municipio_id) }}" id="municipio_id" placeholder="Municipio Id">
            {!! $errors->first('municipio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Direccion') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $almacen?->direccion) }}" id="direccion" placeholder="Direccion">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lat" class="form-label">{{ __('Lat') }}</label>
            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror" value="{{ old('lat', $almacen?->lat) }}" id="lat" placeholder="Lat">
            {!! $errors->first('lat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lon" class="form-label">{{ __('Lon') }}</label>
            <input type="text" name="lon" class="form-control @error('lon') is-invalid @enderror" value="{{ old('lon', $almacen?->lon) }}" id="lon" placeholder="Lon">
            {!! $errors->first('lon', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>