@extends('layouts.app')

@section('page_title', 'Editar municipio')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar municipio</h3>
                </div>
                <form method="post" action="{{ route('cat.municipios.update', $municipio->municipio_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="departamento_id">Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-control" required>
                                @foreach ($departamentos as $dep)
                                    <option value="{{ $dep->departamento_id }}" {{ old('departamento_id', $municipio->departamento_id) == $dep->departamento_id ? 'selected' : '' }}>
                                        {{ $dep->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" maxlength="120" required
                                   value="{{ old('nombre', $municipio->nombre) }}">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.municipios.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


