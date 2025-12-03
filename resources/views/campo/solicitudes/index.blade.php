@extends('layouts.app')

@section('page_title', 'Mis Solicitudes - Planta')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Solicitudes de Producción</h1>
            <p class="text-muted mb-0">Solicitudes enviadas a productores</p>
        </div>
        <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Nueva Solicitud
        </a>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Solicitudes</h3>
        </div>
        <div class="card-body p-0">
            @if(count($solicitudes) > 0)
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Productor</th>
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
                            <tr>
                                <td><strong>{{ $sol->codigo_solicitud }}</strong></td>
                                <td>{{ $sol->productor_nombre }}</td>
                                <td>{{ $sol->variedad_nombre }}</td>
                                <td>{{ number_format($sol->cantidad_solicitada_t, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($sol->fecha_necesaria)->format('d/m/Y') }}</td>
                                <td>
                                    @if($sol->estado === 'PENDIENTE')
                                        <span class="badge badge-warning">Pendiente</span>
                                    @elseif($sol->estado === 'ACEPTADA')
                                        <span class="badge badge-success">Aceptada</span>
                                    @elseif($sol->estado === 'RECHAZADA')
                                        <span class="badge badge-danger">Rechazada</span>
                                    @else
                                        <span class="badge badge-info">{{ $sol->estado }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sol->conductor_asignado)
                                        <i class="fas fa-user-check text-success"></i>
                                        {{ $sol->conductor_asignado }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('solicitudes.show', $sol->solicitud_id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No hay solicitudes registradas</p>
                    <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
                        Crear Primera Solicitud
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
