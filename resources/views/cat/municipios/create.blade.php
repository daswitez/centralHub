@extends('layouts.app')

@section('page_title', 'Nuevo municipio')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Crear municipio</h3>
                </div>
                <form method="post" action="{{ route('cat.municipios.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="departamento_id">Departamento</label>
                            <select id="departamento_id" name="departamento_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach ($departamentos as $dep)
                                    <option value="{{ $dep->departamento_id }}" {{ old('departamento_id') == $dep->departamento_id ? 'selected' : '' }}>
                                        {{ $dep->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" maxlength="120" required
                                   value="{{ old('nombre') }}">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.municipios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


