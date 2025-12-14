@extends('layouts.app')

@section('page_title', 'Mis Solicitudes - Productor')

@section('page_header')
    <div>
        <h1 class="m-0">Solicitudes Recibidas</h1>
        <p class="text-muted mb-0">Solicitudes de producción de plantas</p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mis Solicitudes</h3>
        </div>
        <div class="card-body p-0">
            @if(count($solicitudes) > 0)
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Planta</th>
                            <th>Variedad</th>
                            <th>Cantidad (t)</th>
                            <th>Fecha Necesaria</th>
                            <th>Estado</th>
                            <th>Conductor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $sol)
                            <tr class="{{ $sol->estado === 'PENDIENTE' ? 'table-warning' : '' }}">
                                <td><strong>{{ $sol->codigo_solicitud }}</strong></td>
                                <td>{{ $sol->planta_nombre }}</td>
                                <td>{{ $sol->variedad_nombre }}</td>
                                <td>{{ number_format($sol->cantidad_solicitada_t, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($sol->fecha_necesaria)->format('d/m/Y') }}</td>
                                <td>
                                    @if($sol->estado === 'PENDIENTE')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> Pendiente
                                        </span>
                                    @elseif($sol->estado === 'ACEPTADA')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Aceptada
                                        </span>
                                    @elseif($sol->estado === 'RECHAZADA')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Rechazada
                                        </span>
                                    @else
                                        <span class="badge badge-info">{{ $sol->estado }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sol->conductor_asignado)
                                        <i class="fas fa-truck text-success"></i>
                                        {{ $sol->conductor_asignado }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('solicitudes.show', $sol->solicitud_id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    {{-- Botón y modal de responder removidos - Las respuestas se gestionan desde el microservicio --}}
                                    @if($sol->estado === 'PENDIENTE')
                                        <span class="badge badge-warning small">
                                            <i class="fas fa-info-circle"></i> Pendiente de respuesta
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No has recibido solicitudes</p>
                </div>
            @endif
        </div>
    </div>
@endsection
