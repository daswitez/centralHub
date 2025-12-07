@extends('layouts.app')

@section('page_title', 'Órdenes de Envío')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Órdenes de Envío</h1>
            <p class="text-muted mb-0">Gestión de envíos Planta → Almacén</p>
        </div>
        <a href="{{ route('ordenes-envio.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Orden
        </a>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        @foreach($estados as $e)
                            <option value="{{ $e }}" {{ $estado === $e ? 'selected' : '' }}>{{ $e }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Órdenes --}}
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Lote</th>
                        <th>Planta Origen</th>
                        <th>Almacén Destino</th>
                        <th>Cantidad</th>
                        <th>Conductor</th>
                        <th>Estado</th>
                        <th>Fecha Prog.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordenes as $o)
                        <tr>
                            <td>
                                <strong>{{ $o->codigo_orden }}</strong>
                                @if($o->prioridad === 'URGENTE')
                                    <span class="badge badge-danger ml-1">URGENTE</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $o->codigo_lote_salida }}</span>
                                <br><small class="text-muted">{{ $o->sku }}</small>
                            </td>
                            <td>{{ $o->planta_nombre }}</td>
                            <td>{{ $o->almacen_nombre }}</td>
                            <td>{{ number_format($o->cantidad_t, 2) }} t</td>
                            <td>
                                @if($o->conductor_nombre)
                                    <i class="fas fa-user text-success"></i> {{ $o->conductor_nombre }}
                                    @if($o->vehiculo_placa)
                                        <br><small class="text-muted">{{ $o->vehiculo_placa }}</small>
                                    @endif
                                @else
                                    <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $colorEstado = match($o->estado) {
                                        'PENDIENTE' => 'warning',
                                        'CONDUCTOR_ASIGNADO' => 'info',
                                        'EN_CARGA' => 'primary',
                                        'EN_RUTA' => 'primary',
                                        'ENTREGADO' => 'success',
                                        'CANCELADO' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge badge-{{ $colorEstado }}">{{ $o->estado }}</span>
                            </td>
                            <td>
                                {{ $o->fecha_programada ? \Carbon\Carbon::parse($o->fecha_programada)->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('ordenes-envio.show', $o->orden_envio_id) }}" 
                                   class="btn btn-sm btn-info" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No hay órdenes de envío
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ordenes->hasPages())
            <div class="card-footer">
                {{ $ordenes->links() }}
            </div>
        @endif
    </div>
@endsection
