@extends('layouts.app')

@section('page_title', 'Editar Vehículo')

@section('page_header')
    <div>
        <h1 class="m-0">Editar {{ $vehiculo->codigo_vehiculo }}</h1>
        <p class="text-muted mb-0">Actualizar información del vehículo</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('vehiculos.update', $vehiculo->vehiculo_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Código</label>
                            <input type="text" class="form-control" value="{{ $vehiculo->codigo_vehiculo }}" disabled>
                        </div>

                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" class="form-control" value="{{ $vehiculo->placa }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" name="marca" id="marca" class="form-control" 
                                   required value="{{ old('marca', $vehiculo->marca) }}">
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" name="modelo" id="modelo" class="form-control" 
                                   required value="{{ old('modelo', $vehiculo->modelo) }}">
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado *</label>
                            <select name="estado" id="estado" class="form-control" required>
                                @foreach($estados as $e)
                                    <option value="{{ $e }}" {{ $vehiculo->estado === $e ? 'selected' : '' }}>{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo *</label>
                            <select name="tipo" id="tipo" class="form-control" required>
                                @foreach($tipos as $t)
                                    <option value="{{ $t }}" {{ $vehiculo->tipo === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="capacidad_t">Capacidad (toneladas) *</label>
                            <input type="number" step="0.1" name="capacidad_t" id="capacidad_t" class="form-control"
                                   required min="0.1" value="{{ old('capacidad_t', $vehiculo->capacidad_t) }}">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <input type="number" name="anio" id="anio" class="form-control"
                                           value="{{ old('anio', $vehiculo->anio) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" name="color" id="color" class="form-control"
                                           value="{{ old('color', $vehiculo->color) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kilometraje">Kilometraje</label>
                            <input type="number" name="kilometraje" id="kilometraje" class="form-control"
                                   min="0" value="{{ old('kilometraje', $vehiculo->kilometraje) }}">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="fecha_ultima_revision">Última Revisión</label>
                                    <input type="date" name="fecha_ultima_revision" class="form-control"
                                           value="{{ old('fecha_ultima_revision', $vehiculo->fecha_ultima_revision) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="fecha_proxima_revision">Próxima Revisión</label>
                                    <input type="date" name="fecha_proxima_revision" class="form-control"
                                           value="{{ old('fecha_proxima_revision', $vehiculo->fecha_proxima_revision) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('vehiculos.show', $vehiculo->vehiculo_id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
