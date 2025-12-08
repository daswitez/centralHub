@extends('layouts.app')

@section('page_title', 'Orden ' . $orden->codigo_orden)

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">{{ $orden->codigo_orden }}</h1>
            <p class="text-muted mb-0">Detalle de Orden de Envío</p>
        </div>
        <div>
            <a href="{{ route('ordenes-envio.pdf', $orden->orden_envio_id) }}" class="btn btn-danger mr-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('ordenes-envio.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection


@section('content')
    <div class="row">
        {{-- Estado y Timeline --}}
        <div class="col-lg-8">
            {{-- Estado Actual --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-truck"></i> Estado del Envío</h3>
                </div>
                <div class="card-body">
                    @php
                        $estados = ['PENDIENTE', 'CONDUCTOR_ASIGNADO', 'EN_CARGA', 'EN_RUTA', 'ENTREGADO'];
                        $currentIndex = array_search($orden->estado, $estados);
                        if ($orden->estado === 'CANCELADO') $currentIndex = -1;
                    @endphp
                    
                    <div class="d-flex justify-content-between mb-4">
                        @foreach($estados as $i => $e)
                            <div class="text-center">
                                @php
                                    $icon = match($e) {
                                        'PENDIENTE' => 'clock',
                                        'CONDUCTOR_ASIGNADO' => 'user-check',
                                        'EN_CARGA' => 'box',
                                        'EN_RUTA' => 'truck',
                                        'ENTREGADO' => 'check-circle',
                                        default => 'circle'
                                    };
                                    $color = $i <= $currentIndex ? 'success' : 'secondary';
                                @endphp
                                <div class="rounded-circle d-inline-flex justify-content-center align-items-center bg-{{ $color }}" 
                                     style="width:50px;height:50px;">
                                    <i class="fas fa-{{ $icon }} text-white"></i>
                                </div>
                                <p class="small mb-0 mt-2">{{ str_replace('_', ' ', $e) }}</p>
                            </div>
                            @if($i < count($estados) - 1)
                                <div class="flex-grow-1 d-flex align-items-center px-2">
                                    <div class="w-100 bg-{{ $i < $currentIndex ? 'success' : 'secondary' }}" style="height:3px;"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if($orden->estado === 'CANCELADO')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i> Esta orden fue <strong>CANCELADA</strong>
                        </div>
                    @endif

                    {{-- Botones de Acción --}}
                    @if(!in_array($orden->estado, ['ENTREGADO', 'CANCELADO']))
                        <div class="d-flex gap-2 mt-3">
                            @if($orden->estado === 'PENDIENTE' && !$orden->transportista_id)
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAsignar">
                                    <i class="fas fa-user-plus"></i> Asignar Conductor
                                </button>
                            @endif
                            
                            @if($orden->estado === 'CONDUCTOR_ASIGNADO')
                                <form action="{{ route('ordenes-envio.cambiar-estado', $orden->orden_envio_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="estado" value="EN_CARGA">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-box"></i> Iniciar Carga
                                    </button>
                                </form>
                            @endif
                            
                            @if($orden->estado === 'EN_CARGA')
                                <form action="{{ route('ordenes-envio.cambiar-estado', $orden->orden_envio_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="estado" value="EN_RUTA">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-truck"></i> Iniciar Viaje
                                    </button>
                                </form>
                            @endif
                            
                            @if($orden->estado === 'EN_RUTA')
                                <form action="{{ route('ordenes-envio.cambiar-estado', $orden->orden_envio_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="estado" value="ENTREGADO">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Confirmar Entrega
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detalles del Envío --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Envío</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">ORIGEN</h6>
                            <p><strong>Planta:</strong> {{ $orden->planta_nombre }} ({{ $orden->codigo_planta }})</p>
                            <p><strong>Lote:</strong> {{ $orden->codigo_lote_salida }}</p>
                            <p><strong>SKU:</strong> {{ $orden->sku }}</p>
                            <p><strong>Peso Total:</strong> {{ number_format($orden->peso_t, 2) }} t</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">DESTINO</h6>
                            <p><strong>Almacén:</strong> {{ $orden->almacen_nombre }} ({{ $orden->codigo_almacen }})</p>
                            @if($orden->zona_nombre)
                                <p><strong>Zona:</strong> {{ $orden->zona_nombre }}</p>
                            @endif
                            <p><strong>Cantidad:</strong> {{ number_format($orden->cantidad_t, 2) }} t</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Lateral --}}
        <div class="col-lg-4">
            {{-- Conductor y Vehículo --}}
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-truck"></i> Transporte</h3>
                </div>
                <div class="card-body">
                    @if($orden->conductor_nombre)
                        <div class="mb-3">
                            <h6 class="text-muted">CONDUCTOR</h6>
                            <p class="mb-1"><i class="fas fa-user"></i> <strong>{{ $orden->conductor_nombre }}</strong></p>
                            @if($orden->conductor_telefono)
                                <p class="mb-0"><i class="fas fa-phone"></i> {{ $orden->conductor_telefono }}</p>
                            @endif
                        </div>
                        <div>
                            <h6 class="text-muted">VEHÍCULO</h6>
                            <p class="mb-1"><i class="fas fa-truck"></i> {{ $orden->vehiculo_marca }} {{ $orden->vehiculo_modelo }}</p>
                            <p class="mb-0"><span class="badge badge-secondary">{{ $orden->vehiculo_placa }}</span></p>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Sin conductor asignado
                        </div>
                    @endif
                </div>
            </div>

            {{-- Fechas --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar"></i> Fechas</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="py-2 border-bottom">
                            <small class="text-muted">Creación</small><br>
                            {{ \Carbon\Carbon::parse($orden->fecha_creacion)->format('d/m/Y H:i') }}
                        </li>
                        <li class="py-2 border-bottom">
                            <small class="text-muted">Programada</small><br>
                            {{ $orden->fecha_programada ? \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') : '-' }}
                        </li>
                        @if($orden->fecha_asignacion)
                            <li class="py-2 border-bottom">
                                <small class="text-muted">Asignación</small><br>
                                {{ \Carbon\Carbon::parse($orden->fecha_asignacion)->format('d/m/Y H:i') }}
                            </li>
                        @endif
                        @if($orden->fecha_salida)
                            <li class="py-2 border-bottom">
                                <small class="text-muted">Salida</small><br>
                                {{ \Carbon\Carbon::parse($orden->fecha_salida)->format('d/m/Y H:i') }}
                            </li>
                        @endif
                        @if($orden->fecha_llegada)
                            <li class="py-2">
                                <small class="text-muted">Llegada</small><br>
                                {{ \Carbon\Carbon::parse($orden->fecha_llegada)->format('d/m/Y H:i') }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Prioridad --}}
            <div class="card">
                <div class="card-body text-center">
                    <h6 class="text-muted">PRIORIDAD</h6>
                    @php
                        $colorPrioridad = match($orden->prioridad) {
                            'URGENTE' => 'danger',
                            'BAJA' => 'secondary',
                            default => 'primary'
                        };
                    @endphp
                    <span class="badge badge-{{ $colorPrioridad }} p-2" style="font-size: 1.1rem;">
                        {{ $orden->prioridad }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Asignar Conductor --}}
    <div class="modal fade" id="modalAsignar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ordenes-envio.asignar-conductor', $orden->orden_envio_id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar Conductor y Vehículo</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Conductor</label>
                            <select name="transportista_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                {{-- Esto se llenará dinámicamente --}}
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Vehículo</label>
                            <select name="vehiculo_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                {{-- Esto se llenará dinámicamente --}}
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
