@extends('layouts.app')

@section('page_title', 'Editar productor')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar productor</h3>
                </div>
                <form method="post" action="{{ route('campo.productores.update', $productor->productor_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_productor">Código</label>
                            <input id="codigo_productor" name="codigo_productor" class="form-control" maxlength="40" required value="{{ old('codigo_productor', $productor->codigo_productor) }}">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" class="form-control" maxlength="140" required value="{{ old('nombre', $productor->nombre) }}">
                        </div>
                        <div class="form-group">
                            <label for="municipio_id">Municipio</label>
                            <select id="municipio_id" name="municipio_id" class="form-control" required>
                                @foreach($municipios as $m)
                                    <option value="{{ $m->municipio_id }}" {{ old('municipio_id', $productor->municipio_id) == $m->municipio_id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input id="telefono" name="telefono" class="form-control" maxlength="40" value="{{ old('telefono', $productor->telefono) }}">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('campo.productores.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


