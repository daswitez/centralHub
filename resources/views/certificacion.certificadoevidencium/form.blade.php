<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="evidencia_id" class="form-label">{{ __('Evidencia Id') }}</label>
            <input type="text" name="evidencia_id" class="form-control @error('evidencia_id') is-invalid @enderror" value="{{ old('evidencia_id', $certificacion.certificadoevidencium?->evidencia_id) }}" id="evidencia_id" placeholder="Evidencia Id">
            {!! $errors->first('evidencia_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="certificado_id" class="form-label">{{ __('Certificado Id') }}</label>
            <input type="text" name="certificado_id" class="form-control @error('certificado_id') is-invalid @enderror" value="{{ old('certificado_id', $certificacion.certificadoevidencium?->certificado_id) }}" id="certificado_id" placeholder="Certificado Id">
            {!! $errors->first('certificado_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $certificacion.certificadoevidencium?->tipo) }}" id="tipo" placeholder="Tipo">
            {!! $errors->first('tipo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $certificacion.certificadoevidencium?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="url_archivo" class="form-label">{{ __('Url Archivo') }}</label>
            <input type="text" name="url_archivo" class="form-control @error('url_archivo') is-invalid @enderror" value="{{ old('url_archivo', $certificacion.certificadoevidencium?->url_archivo) }}" id="url_archivo" placeholder="Url Archivo">
            {!! $errors->first('url_archivo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_registro" class="form-label">{{ __('Fecha Registro') }}</label>
            <input type="text" name="fecha_registro" class="form-control @error('fecha_registro') is-invalid @enderror" value="{{ old('fecha_registro', $certificacion.certificadoevidencium?->fecha_registro) }}" id="fecha_registro" placeholder="Fecha Registro">
            {!! $errors->first('fecha_registro', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>