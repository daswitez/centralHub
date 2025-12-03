@extends('layouts.app')

@section('page_title', 'Detalle Solicitud')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">{{ $solicitud->codigo_solicitud }}</h1>
            <p class="text-muted mb-0">Detalle de solicitud de producción</p>
        </div>
        <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            {{-- Información Principal --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información de la Solicitud</h3>
                    <div class="card-tools">
                        @if($solicitud->estado === 'PENDIENTE')
                            <span class="badge badge-warning badge-lg">Pendiente</span>
                        @elseif($solicitud->estado === 'ACEPTADA')
                            <span class="badge badge-success badge-lg">Aceptada</span>
                        @elseif($solicitud->estado === 'RECHAZADA')
                            <span class="badge badge-danger badge-lg">Rechazada</span>
                        @else
                            <span class="badge badge-info badge-lg">{{ $solicitud->estado }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Planta Solicitante:</strong></p>
                            <p class="text-muted">{{ $solicitud->planta_nombre }} ({{ $solicitud->codigo_planta }})</p>

                            <p><strong>Productor:</strong></p>
                            <p class="text-muted">{{ $solicitud->productor_nombre }} ({{ $solicitud->codigo_productor }})</p>

                            <p><strong>Variedad:</strong></p>
                            <p class="text-muted">{{ $solicitud->variedad_nombre }} ({{ $solicitud->codigo_variedad }})</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Cantidad Solicitada:</strong></p>
                            <p class="text-muted">{{ number_format($solicitud->cantidad_solicitada_t, 2) }} toneladas</p>

                            <p><strong>Fecha Necesaria:</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($solicitud->fecha_necesaria)->format('d/m/Y') }}</p>

                            <p><strong>Fecha de Solicitud:</strong></p>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($solicitud->observaciones)
                        <hr>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted">{{ $solicitud->observaciones }}</p>
                    @endif
                </div>
            </div>

            {{-- Información de Respuesta --}}
            @if($solicitud->estado !== 'PENDIENTE')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Respuesta del Productor</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha de Respuesta:</strong></p>
                        <p class="text-muted">{{ \Carbon\Carbon::parse($solicitud->fecha_respuesta)->format('d/m/Y H:i') }}</p>

                        @if($solicitud->estado === 'RECHAZADA' && $solicitud->justificacion_rechazo)
                            <hr>
                            <p><strong>Justificación del Rechazo:</strong></p>
                            <div class="alert alert-danger">
                                {{ $solicitud->justificacion_rechazo }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Columna Lateral --}}
        <div class="col-md-4">
            {{-- Información del Conductor --}}
            @if($solicitud->conductor_nombre)
                <div class="card bg-success">
                    <div class="card-body">
                        <h5 clasS="card-title">
                            <i class="fas fa-truck"></i> Conductor Asignado
                        </h5>
                        <hr class="bg-white">
                        <p class="mb-1"><strong>{{ $solicitud->conductor_nombre }}</strong></p>
                        @if($solicitud->conductor_telefono)
                            <p class="mb-1">
                                <i class="fas fa-phone"></i> 
                                {{ $solicitud->conductor_telefono }}
                            </p>
                        @endif
                        
                        <hr class="bg-white">
                        <p class="mb-1"><small>Asignado:</small></p>
                        <p class="mb-1">
                            {{ \Carbon\Carbon::parse($solicitud->fecha_asignacion)->format('d/m/Y H:i') }}
                        </p>

                        <p class="mb-1 mt-2"><small>Estado:</small></p>
                        <p class="mb-0">
                            @if($solicitud->estado_asignacion === 'ASIGNADO')
                                <span class="badge badge-light">Asignado</span>
                            @elseif($solicitud->estado_asignacion === 'EN_RUTA')
                                <span class="badge badge-warning">En Ruta</span>
                            @elseif($solicitud->estado_asignacion === 'COMPLETADO')
                                <span class="badge badge-success">Completado</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Estado de la Solicitud</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-primary">
                                {{ \Carbon\Carbon::parse($solicitud->fecha_solicitud)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-envelope bg-blue"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Solicitud Creada</h3>
                                <div class="timeline-body">
                                    Planta {{ $solicitud->planta_nombre }} solicitó productos
                                </div>
                            </div>
                        </div>

                        @if($solicitud->fecha_respuesta)
                            <div class="time-label">
                                <span class="bg-{{ $solicitud->estado === 'ACEPTADA' ? 'success' : 'danger' }}">
                                    {{ \Carbon\Carbon::parse($solicitud->fecha_respuesta)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div>
                                <i class="fas fa-{{ $solicitud->estado === 'ACEPTADA' ? 'check' : 'times' }} bg-{{ $solicitud->estado === 'ACEPTADA' ? 'green' : 'red' }}"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">
                                        Solicitud {{ $solicitud->estado === 'ACEPTADA' ? 'Aceptada' : 'Rechazada' }}
                                    </h3>
                                </div>
                            </div>
                        @endif

                        @if($solicitud->fecha_asignacion)
                            <div>
                                <i class="fas fa-truck bg-purple"></i>
                                <div class="timeline-item">
                                    <h3 class="timeline-header">Conductor Asignado</h3>
                                    <div class="timeline-body">
                                        {{ $solicitud->conductor_nombre }} asignado automáticamente
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
