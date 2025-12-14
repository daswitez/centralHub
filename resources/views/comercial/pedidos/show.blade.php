@extends('layouts.app')

@section('title', 'Detalle de Pedido')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-file-invoice mr-2"></i>
                    Pedido {{ $pedido->codigo_pedido }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('comercial.pedidos.index') }}">Pedidos</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            {{-- Columna izquierda: Info del pedido --}}
            <div class="col-lg-4">
                {{-- Estado actual --}}
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $estadoConfig = match($pedido->estado) {
                                'PENDIENTE', 'ABIERTO' => ['color' => 'warning', 'icon' => 'clock', 'texto' => 'Pendiente'],
                                'PREPARANDO' => ['color' => 'info', 'icon' => 'box', 'texto' => 'Preparando'],
                                'ENVIADO' => ['color' => 'primary', 'icon' => 'truck', 'texto' => 'Enviado'],
                                'ENTREGADO' => ['color' => 'success', 'icon' => 'check-circle', 'texto' => 'Entregado'],
                                'CANCELADO' => ['color' => 'danger', 'icon' => 'times-circle', 'texto' => 'Cancelado'],
                                default => ['color' => 'secondary', 'icon' => 'question', 'texto' => $pedido->estado]
                            };
                        @endphp
                        <div class="display-4 text-{{ $estadoConfig['color'] }} mb-3">
                            <i class="fas fa-{{ $estadoConfig['icon'] }}"></i>
                        </div>
                        <h3 class="text-{{ $estadoConfig['color'] }}">{{ $estadoConfig['texto'] }}</h3>
                        
                        {{-- Formulario de cambio de estado removido - Los cambios se hacen desde el microservicio de Trazabilidad --}}
                        @if(isset($estados_disponibles) && count($estados_disponibles) > 0)
                            <hr>
                            <p class="text-muted small">
                                <i class="fas fa-info-circle"></i> 
                                Los cambios de estado se gestionan desde el sistema de Trazabilidad
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Información del pedido --}}
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-info-circle mr-2"></i>Información del Pedido
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <tr>
                                <td><strong>Código</strong></td>
                                <td>{{ $pedido->codigo_pedido }}</td>
                            </tr>
                            <tr>
                                <td><strong>Fecha</strong></td>
                                <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Almacén</strong></td>
                                <td>{{ $pedido->almacen_nombre ?? 'No asignado' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Información del cliente --}}
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-user mr-2"></i>Cliente
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <tr>
                                <td><strong>Nombre</strong></td>
                                <td>{{ $pedido->cliente_nombre }}</td>
                            </tr>
                            <tr>
                                <td><strong>Código</strong></td>
                                <td>{{ $pedido->codigo_cliente }}</td>
                            </tr>
                            @if($pedido->cliente_tipo)
                            <tr>
                                <td><strong>Tipo</strong></td>
                                <td>{{ $pedido->cliente_tipo }}</td>
                            </tr>
                            @endif
                            @if($pedido->cliente_direccion)
                            <tr>
                                <td><strong>Dirección</strong></td>
                                <td>{{ $pedido->cliente_direccion }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: Detalles del pedido --}}
            <div class="col-lg-8">
                {{-- KPIs --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $total_items }}</h3>
                                <p>Productos</p>
                            </div>
                            <div class="icon"><i class="fas fa-cubes"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($total_cantidad, 2) }} T</h3>
                                <p>Cantidad Total</p>
                            </div>
                            <div class="icon"><i class="fas fa-weight-hanging"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>${{ number_format($total_monto, 2) }}</h3>
                                <p>Monto Total USD</p>
                            </div>
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>

                {{-- Timeline de estados --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-stream mr-2"></i>Flujo del Pedido</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @php
                                $estados = ['PENDIENTE', 'PREPARANDO', 'ENVIADO', 'ENTREGADO'];
                                $estadoActual = in_array($pedido->estado, $estados) 
                                    ? array_search($pedido->estado, $estados) 
                                    : (in_array('ABIERTO', [$pedido->estado]) ? 0 : -1);
                                $isCancelado = $pedido->estado === 'CANCELADO';
                            @endphp
                            @foreach($estados as $idx => $estado)
                                <div class="col-3">
                                    @php
                                        $isActive = !$isCancelado && $idx <= $estadoActual;
                                        $iconClass = $isActive ? 'text-success' : 'text-muted';
                                        $stepIcon = match($estado) {
                                            'PENDIENTE' => 'clock',
                                            'PREPARANDO' => 'box',
                                            'ENVIADO' => 'truck',
                                            'ENTREGADO' => 'check-circle',
                                            default => 'circle'
                                        };
                                    @endphp
                                    <div class="h2 {{ $iconClass }}">
                                        <i class="fas fa-{{ $stepIcon }}"></i>
                                    </div>
                                    <small class="{{ $isActive ? 'font-weight-bold' : 'text-muted' }}">{{ $estado }}</small>
                                </div>
                            @endforeach
                        </div>
                        @if($isCancelado)
                            <div class="alert alert-danger text-center mt-3 mb-0">
                                <i class="fas fa-times-circle mr-2"></i>Este pedido fue cancelado
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Detalle de productos --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list mr-2"></i>Detalle de Productos</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>SKU</th>
                                    <th class="text-right">Cantidad (T)</th>
                                    <th class="text-right">Precio Unit. USD</th>
                                    <th class="text-right">Subtotal USD</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detalles as $idx => $detalle)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong>{{ $detalle->sku }}</strong></td>
                                        <td class="text-right">{{ number_format($detalle->cantidad_t, 2) }}</td>
                                        <td class="text-right">${{ number_format($detalle->precio_unit_usd, 2) }}</td>
                                        <td class="text-right"><strong>${{ number_format($detalle->subtotal, 2) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="2">TOTAL</th>
                                    <th class="text-right">{{ number_format($total_cantidad, 2) }} T</th>
                                    <th></th>
                                    <th class="text-right text-success h5">${{ number_format($total_monto, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Botones de acción --}}
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('comercial.pedidos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
