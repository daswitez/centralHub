@extends('layouts.app')

@section('page_title', 'Nueva Solicitud')

@section('page_header')
    <div>
        <h1 class="m-0">Nueva Solicitud de Producción</h1>
        <p class="text-muted mb-0">Solicitar productos a productores</p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos de la Solicitud</h3>
                </div>
                <form method="POST" action="{{ route('solicitudes.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="planta_id">Planta Solicitante *</label>
                            <select id="planta_id" name="planta_id" class="form-control" required>
                                <option value="">Seleccione planta...</option>
                                @foreach($plantas as $planta)
                                    <option value="{{ $planta->planta_id }}" 
                                            {{ old('planta_id') == $planta->planta_id ? 'selected' : '' }}>
                                        {{ $planta->nombre }} ({{ $planta->codigo_planta }})
                                    </option>
                                @endforeach
                            </select>
                            @error('planta_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="productor_id">Productor *</label>
                            <select id="productor_id" name="productor_id" class="form-control" required>
                                <option value="">Seleccione productor...</option>
                                @foreach($productores as $prod)
                                    <option value="{{ $prod->productor_id }}"
                                            {{ old('productor_id') == $prod->productor_id ? 'selected' : '' }}>
                                        {{ $prod->nombre }} ({{ $prod->codigo_productor }})
                                    </option>
                                @endforeach
                            </select>
                            @error('productor_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="variedad_id">Variedad de Papa *</label>
                            <select id="variedad_id" name="variedad_id" class="form-control" required>
                                <option value="">Seleccione variedad...</option>
                                @foreach($variedades as $var)
                                    <option value="{{ $var->variedad_id }}"
                                            {{ old('variedad_id') == $var->variedad_id ? 'selected' : '' }}>
                                        {{ $var->nombre_comercial }} ({{ $var->codigo_variedad }})
                                    </option>
                                @endforeach
                            </select>
                            @error('variedad_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cantidad_solicitada_t">Cantidad Solicitada (toneladas) *</label>
                                    <input type="number" 
                                           id="cantidad_solicitada_t" 
                                           name="cantidad_solicitada_t"
                                           class="form-control" 
                                           step="0.01" 
                                           min="0.01"
                                           value="{{ old('cantidad_solicitada_t') }}"
                                           required
                                           placeholder="Ej: 5.50">
                                    @error('cantidad_solicitada_t')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_necesaria">Fecha Necesaria *</label>
                                    <input type="date" 
                                           id="fecha_necesaria" 
                                           name="fecha_necesaria"
                                           class="form-control"
                                           value="{{ old('fecha_necesaria') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                    @error('fecha_necesaria')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones" 
                                      name="observaciones"
                                      class="form-control" 
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Detalles adicionales sobre la solicitud...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane mr-1"></i> Enviar Solicitud
                        </button>
                        <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Información</h5>
                    <hr>
                    <p class="mb-2"><strong>¿Qué sucede al enviar?</strong></p>
                    <ol class="pl-3">
                        <li>Se genera un código único (ej: SOL-2025-001)</li>
                        <li>El productor recibe la solicitud</li>
                        <li>El productor puede aceptar o rechazar</li>
                        <li>Si acepta, se asigna un conductor automáticamente</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
