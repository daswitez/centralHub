@extends('layouts.app')

@section('page_title', 'Nuevo Vehículo')

@section('page_header')
    <div>
        <h1 class="m-0">Registrar Vehículo</h1>
        <p class="text-muted mb-0">Agregar nuevo vehículo a la flota</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('vehiculos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="codigo_vehiculo">Código *</label>
                            <input type="text" name="codigo_vehiculo" id="codigo_vehiculo" 
                                   class="form-control @error('codigo_vehiculo') is-invalid @enderror"
                                   placeholder="VEH-005" required value="{{ old('codigo_vehiculo') }}">
                            @error('codigo_vehiculo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="placa">Placa *</label>
                            <input type="text" name="placa" id="placa" 
                                   class="form-control @error('placa') is-invalid @enderror"
                                   placeholder="1234-ABC" required value="{{ old('placa') }}">
                            @error('placa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" name="marca" id="marca" 
                                   class="form-control @error('marca') is-invalid @enderror"
                                   required value="{{ old('marca') }}">
                            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" name="modelo" id="modelo" 
                                   class="form-control @error('modelo') is-invalid @enderror"
                                   required value="{{ old('modelo') }}">
                            @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo *</label>
                            <select name="tipo" id="tipo" class="form-control" required>
                                @foreach($tipos as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="capacidad_t">Capacidad (toneladas) *</label>
                            <input type="number" step="0.1" name="capacidad_t" id="capacidad_t" 
                                   class="form-control @error('capacidad_t') is-invalid @enderror"
                                   required min="0.1" value="{{ old('capacidad_t') }}">
                            @error('capacidad_t')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="anio">Año</label>
                                    <input type="number" name="anio" id="anio" class="form-control"
                                           min="1990" max="{{ date('Y') + 1 }}" value="{{ old('anio') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="text" name="color" id="color" class="form-control"
                                           value="{{ old('color') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kilometraje">Kilometraje</label>
                            <input type="number" name="kilometraje" id="kilometraje" class="form-control"
                                   min="0" value="{{ old('kilometraje', 0) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Vehículo
                </button>
                <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
