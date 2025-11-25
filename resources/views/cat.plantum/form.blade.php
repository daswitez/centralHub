<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="planta_id" class="form-label">{{ __('Planta Id') }}</label>
            <input type="text" name="planta_id" class="form-control @error('planta_id') is-invalid @enderror" value="{{ old('planta_id', $cat.plantum?->planta_id) }}" id="planta_id" placeholder="Planta Id">
            {!! $errors->first('planta_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_planta" class="form-label">{{ __('Codigo Planta') }}</label>
            <input type="text" name="codigo_planta" class="form-control @error('codigo_planta') is-invalid @enderror" value="{{ old('codigo_planta', $cat.plantum?->codigo_planta) }}" id="codigo_planta" placeholder="Codigo Planta">
            {!! $errors->first('codigo_planta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cat.plantum?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="municipio_id" class="form-label">{{ __('Municipio Id') }}</label>
            <input type="text" name="municipio_id" class="form-control @error('municipio_id') is-invalid @enderror" value="{{ old('municipio_id', $cat.plantum?->municipio_id) }}" id="municipio_id" placeholder="Municipio Id">
            {!! $errors->first('municipio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Direccion') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $cat.plantum?->direccion) }}" id="direccion" placeholder="Direccion">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lat" class="form-label">{{ __('Lat') }}</label>
            <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror" value="{{ old('lat', $cat.plantum?->lat) }}" id="lat" placeholder="Lat">
            {!! $errors->first('lat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="lon" class="form-label">{{ __('Lon') }}</label>
            <input type="text" name="lon" class="form-control @error('lon') is-invalid @enderror" value="{{ old('lon', $cat.plantum?->lon) }}" id="lon" placeholder="Lon">
            {!! $errors->first('lon', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>