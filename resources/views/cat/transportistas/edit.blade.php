@extends('layouts.app')

@section('page_title', 'Editar transportista')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar transportista</h3>
                </div>
                <form method="post" action="{{ route('cat.transportistas.update', $transportista->transportista_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_transp">Código</label>
                            <input id="codigo_transp" name="codigo_transp" class="form-control" maxlength="40" required value="{{ old('codigo_transp', $transportista->codigo_transp) }}">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" class="form-control" maxlength="140" required value="{{ old('nombre', $transportista->nombre) }}">
                        </div>
                        <div class="form-group">
                            <label for="nro_licencia">Nro. licencia</label>
                            <input id="nro_licencia" name="nro_licencia" class="form-control" maxlength="60" value="{{ old('nro_licencia', $transportista->nro_licencia) }}">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.transportistas.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


