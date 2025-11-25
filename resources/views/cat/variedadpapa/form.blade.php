<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="variedad_id" class="form-label">{{ __('Variedad Id') }}</label>
            <input type="text" name="variedad_id" class="form-control @error('variedad_id') is-invalid @enderror" value="{{ old('variedad_id', $variedadpapa?->variedad_id) }}" id="variedad_id" placeholder="Variedad Id">
            {!! $errors->first('variedad_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="codigo_variedad" class="form-label">{{ __('Codigo Variedad') }}</label>
            <input type="text" name="codigo_variedad" class="form-control @error('codigo_variedad') is-invalid @enderror" value="{{ old('codigo_variedad', $variedadpapa?->codigo_variedad) }}" id="codigo_variedad" placeholder="Codigo Variedad">
            {!! $errors->first('codigo_variedad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_comercial" class="form-label">{{ __('Nombre Comercial') }}</label>
            <input type="text" name="nombre_comercial" class="form-control @error('nombre_comercial') is-invalid @enderror" value="{{ old('nombre_comercial', $variedadpapa?->nombre_comercial) }}" id="nombre_comercial" placeholder="Nombre Comercial">
            {!! $errors->first('nombre_comercial', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="aptitud" class="form-label">{{ __('Aptitud') }}</label>
            <input type="text" name="aptitud" class="form-control @error('aptitud') is-invalid @enderror" value="{{ old('aptitud', $variedadpapa?->aptitud) }}" id="aptitud" placeholder="Aptitud">
            {!! $errors->first('aptitud', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciclo_dias_min" class="form-label">{{ __('Ciclo Dias Min') }}</label>
            <input type="text" name="ciclo_dias_min" class="form-control @error('ciclo_dias_min') is-invalid @enderror" value="{{ old('ciclo_dias_min', $variedadpapa?->ciclo_dias_min) }}" id="ciclo_dias_min" placeholder="Ciclo Dias Min">
            {!! $errors->first('ciclo_dias_min', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ciclo_dias_max" class="form-label">{{ __('Ciclo Dias Max') }}</label>
            <input type="text" name="ciclo_dias_max" class="form-control @error('ciclo_dias_max') is-invalid @enderror" value="{{ old('ciclo_dias_max', $variedadpapa?->ciclo_dias_max) }}" id="ciclo_dias_max" placeholder="Ciclo Dias Max">
            {!! $errors->first('ciclo_dias_max', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>