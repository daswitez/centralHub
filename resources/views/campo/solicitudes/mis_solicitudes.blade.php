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
                                    @if($sol->estado === 'PENDIENTE')
                                        <button type="button" 
                                                class="btn btn-sm btn-success"
                                                data-toggle="modal" 
                                                data-target="#modalResponder{{ $sol->solicitud_id }}">
                                            <i class="fas fa-reply"></i> Responder
                                        </button>

                                        {{-- Modal para responder --}}
                                        <div class="modal fade" id="modalResponder{{ $sol->solicitud_id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('solicitudes.responder', $sol->solicitud_id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Responder Solicitud</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>{{ $sol->codigo_solicitud }}</strong></p>
                                                            <p>Planta: {{ $sol->planta_nombre }}</p>
                                                            <p>Cantidad: {{ number_format($sol->cantidad_solicitada_t, 2) }} t</p>
                                                            <hr>
                                                            
                                                            <div class="form-group">
                                                                <label>Decisión *</label>
                                                                <select name="decision" class="form-control" required onchange="toggleJustificacion(this, {{ $sol->solicitud_id }})">
                                                                    <option value="">Seleccione...</option>
                                                                    <option value="ACEPTAR">✓ Aceptar</option>
                                                                    <option value="RECHAZAR">✗ Rechazar</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group" id="justificacionDiv{{ $sol->solicitud_id }}" style="display: none;">
                                                                <label>Justificación de Rechazo *</label>
                                                                <textarea name="justificacion_rechazo" 
                                                                          class="form-control" 
                                                                          rows="3"
                                                                          placeholder="Explique por qué rechaza la solicitud..."></textarea>
                                                            </div>

                                                            <div class="alert alert-info" id="infoAceptar{{ $sol->solicitud_id }}" style="display: none;">
                                                                <i class="fas fa-info-circle"></i>
                                                                Al aceptar, se asignará automáticamente un conductor disponible.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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

    <script>
        function toggleJustificacion(select, id) {
            const justDiv = document.getElementById(`justificacionDiv${id}`);
            const infoDiv = document.getElementById(`infoAceptar${id}`);
            
            if (select.value === 'RECHAZAR') {
                justDiv.style.display = 'block';
                infoDiv.style.display = 'none';
                justDiv.querySelector('textarea').required = true;
            } else if (select.value === 'ACEPTAR') {
                justDiv.style.display = 'none';
                infoDiv.style.display = 'block';
                justDiv.querySelector('textarea').required = false;
            } else {
                justDiv.style.display = 'none';
                infoDiv.style.display = 'none';
                justDiv.querySelector('textarea').required = false;
            }
        }
    </script>
@endsection
