@extends('layouts.app')

@section('page_title', 'Vehículo ' . $vehiculo->codigo_vehiculo)

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">{{ $vehiculo->codigo_vehiculo }}</h1>
            <p class="text-muted mb-0">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</p>
        </div>
        <div>
            <a href="{{ route('vehiculos.edit', $vehiculo->vehiculo_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-truck"></i> Información del Vehículo</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Placa:</strong></p>
                            <h3><span class="badge badge-dark">{{ $vehiculo->placa }}</span></h3>
                        </div>
                        <div class="col-6">
                            <p><strong>Estado:</strong></p>
                            @php
                                $colorEstado = match($vehiculo->estado) {
                                    'DISPONIBLE' => 'success',
                                    'EN_USO' => 'primary',
                                    'MANTENIMIENTO' => 'warning',
                                    default => 'danger'
                                };
                            @endphp
                            <h3><span class="badge badge-{{ $colorEstado }}">{{ $vehiculo->estado }}</span></h3>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Marca:</strong> {{ $vehiculo->marca }}</p>
                            <p><strong>Modelo:</strong> {{ $vehiculo->modelo }}</p>
                            <p><strong>Año:</strong> {{ $vehiculo->anio ?? 'N/A' }}</p>
                            <p><strong>Color:</strong> {{ $vehiculo->color ?? 'N/A' }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Tipo:</strong> 
                                <span class="badge badge-info">{{ $vehiculo->tipo }}</span>
                            </p>
                            <p><strong>Capacidad:</strong> {{ number_format($vehiculo->capacidad_t, 1) }} t</p>
                            <p><strong>Kilometraje:</strong> {{ number_format($vehiculo->kilometraje) }} km</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-user"></i> Conductor Asignado</h3>
                </div>
                <div class="card-body">
                    @if($vehiculo->conductor_nombre)
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="fas fa-user-circle fa-4x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $vehiculo->conductor_nombre }}</h4>
                                <p class="mb-0 text-muted">{{ $vehiculo->conductor_codigo }}</p>
                                @if($vehiculo->conductor_telefono)
                                    <p class="mb-0"><i class="fas fa-phone"></i> {{ $vehiculo->conductor_telefono }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> No hay conductor asignado a este vehículo
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar"></i> Documentación</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Última revisión</span>
                            <strong>{{ $vehiculo->fecha_ultima_revision ?? 'N/A' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Próxima revisión</span>
                            <strong>{{ $vehiculo->fecha_proxima_revision ?? 'N/A' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Vencimiento seguro</span>
                            <strong>{{ $vehiculo->vencimiento_seguro ?? 'N/A' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Vencimiento inspección</span>
                            <strong>{{ $vehiculo->vencimiento_inspeccion ?? 'N/A' }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial de Envíos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history"></i> Últimos Envíos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Código Envío</th>
                        <th>Conductor</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($envios as $e)
                        <tr>
                            <td>{{ $e->codigo_envio }}</td>
                            <td>{{ $e->conductor }}</td>
                            <td>{{ \Carbon\Carbon::parse($e->fecha_salida)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $e->estado === 'COMPLETADO' ? 'success' : 'primary' }}">
                                    {{ $e->estado }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                No hay envíos registrados con este vehículo
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
