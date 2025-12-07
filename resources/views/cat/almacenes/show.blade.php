@extends('layouts.app')

@section('page_title', 'Detalle Almacén')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">{{ $almacen->nombre }}</h1>
            <p class="text-muted mb-0">{{ $almacen->codigo_almacen }}</p>
        </div>
        <div>
            <a href="{{ route('cat.almacenes.edit', $almacen->almacen_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('cat.almacenes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection

@section('content')
    {{-- Información General --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información General</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dirección:</strong> {{ $almacen->direccion ?? 'No especificada' }}</p>
                            <p><strong>Ubicación:</strong> {{ $almacen->municipio->nombre ?? 'N/A' }}</p>
                            <p><strong>Responsable:</strong> {{ $almacen->responsable ?? 'No asignado' }}</p>
                            <p><strong>Teléfono:</strong> {{ $almacen->telefono ?? '-' }}</p>
                            <p><strong>Email:</strong> {{ $almacen->email ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tipo:</strong> 
                                <span class="badge badge-info">{{ $almacen->tipo ?? 'CENTRAL' }}</span>
                            </p>
                            <p><strong>Estado:</strong> 
                                <span class="badge badge-{{ ($almacen->estado ?? 'ACTIVO') === 'ACTIVO' ? 'success' : 'warning' }}">
                                    {{ $almacen->estado ?? 'ACTIVO' }}
                                </span>
                            </p>
                            <p><strong>Horario:</strong> {{ $almacen->horario_operacion ?? '24/7' }}</p>
                            @if($almacen->temperatura_min_c || $almacen->temperatura_max_c)
                                <p><strong>Temperatura:</strong> 
                                    {{ $almacen->temperatura_min_c }}°C - {{ $almacen->temperatura_max_c }}°C
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Capacidad</h3>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $stats['ocupacion_pct'] }}%</h2>
                    <p class="text-muted">Ocupación</p>
                    
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $stats['ocupacion_pct'] > 80 ? 'bg-danger' : ($stats['ocupacion_pct'] > 50 ? 'bg-warning' : 'bg-success') }}" 
                             style="width: {{ $stats['ocupacion_pct'] }}%">
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-6">
                            <h4>{{ number_format($almacen->capacidad_total_t ?? 0, 1) }}t</h4>
                            <small class="text-muted">Total</small>
                        </div>
                        <div class="col-6">
                            <h4>{{ number_format($almacen->capacidad_disponible_t ?? 0, 1) }}t</h4>
                            <small class="text-muted">Disponible</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cubes"></i> Resumen</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Zonas</span>
                            <strong>{{ $stats['total_zonas'] }}</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Ubicaciones</span>
                            <strong>{{ $stats['total_ubicaciones'] }}</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Ubicaciones Ocupadas</span>
                            <strong>{{ $stats['ubicaciones_ocupadas'] }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Zonas del Almacén --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-th-large"></i> Zonas de Almacenamiento</h3>
        </div>
        <div class="card-body">
            @if(count($zonas) > 0)
                <div class="row">
                    @foreach($zonas as $zona)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 {{ $zona->estado === 'LLENO' ? 'border-danger' : ($zona->estado === 'MANTENIMIENTO' ? 'border-warning' : 'border-success') }}">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $zona->codigo_zona }}</strong>
                                        <span class="badge badge-{{ $zona->tipo === 'REFRIGERADO' ? 'info' : ($zona->tipo === 'CONGELADO' ? 'primary' : 'secondary') }}">
                                            {{ $zona->tipo }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5>{{ $zona->nombre }}</h5>
                                    
                                    <div class="progress mb-2" style="height: 15px;">
                                        <div class="progress-bar bg-info" style="width: {{ $zona->ocupacion_pct }}%">
                                            {{ $zona->ocupacion_pct }}%
                                        </div>
                                    </div>
                                    
                                    <p class="mb-1">
                                        <small>Capacidad: <strong>{{ number_format($zona->capacidad_t, 1) }}t</strong></small>
                                    </p>
                                    <p class="mb-1">
                                        <small>Ubicaciones: <strong>{{ $zona->ubicaciones_ocupadas }}/{{ $zona->ubicaciones_count }}</strong></small>
                                    </p>
                                    @if($zona->temperatura_objetivo_c)
                                        <p class="mb-0">
                                            <small><i class="fas fa-thermometer-half text-info"></i> {{ $zona->temperatura_objetivo_c }}°C</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <small class="text-{{ $zona->estado === 'DISPONIBLE' ? 'success' : 'warning' }}">
                                        <i class="fas fa-circle"></i> {{ $zona->estado }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Este almacén aún no tiene zonas configuradas.
                </div>
            @endif
        </div>
    </div>
@endsection
