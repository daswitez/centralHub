@extends('layouts.app')

@section('title', 'Dashboard de Almacén')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-warehouse mr-2"></i>
                    Dashboard de Almacén
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Almacén</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        {{-- KPIs --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($kpi_total_stock, 2) }} <small>T</small></h3>
                        <p>Stock Total</p>
                    </div>
                    <div class="icon"><i class="fas fa-boxes"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $kpi_total_almacenes }}</h3>
                        <p>Almacenes Activos</p>
                    </div>
                    <div class="icon"><i class="fas fa-warehouse"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $kpi_total_skus }}</h3>
                        <p>SKUs en Stock</p>
                    </div>
                    <div class="icon"><i class="fas fa-barcode"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $kpi_recepciones_hoy }}</h3>
                        <p>Recepciones Hoy</p>
                    </div>
                    <div class="icon"><i class="fas fa-truck-loading"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Stock por Almacén --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h3 class="card-title text-white">
                            <i class="fas fa-chart-bar mr-2"></i>Stock por Almacén
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Almacén</th>
                                    <th class="text-right">Stock (T)</th>
                                    <th class="text-right">SKUs</th>
                                    <th class="text-right">Lotes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stock_por_almacen as $almacen)
                                    <tr>
                                        <td>
                                            <strong>{{ $almacen->codigo_almacen }}</strong><br>
                                            <small class="text-muted">{{ $almacen->almacen_nombre }}</small>
                                        </td>
                                        <td class="text-right">
                                            @if($almacen->stock_total > 0)
                                                <span class="badge badge-success">{{ number_format($almacen->stock_total, 2) }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ $almacen->total_skus }}</td>
                                        <td class="text-right">{{ $almacen->total_lotes }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No hay inventario registrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Stock Detallado por SKU --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white">
                            <i class="fas fa-list mr-2"></i>Top 20 SKUs con Stock
                        </h3>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="thead-light" style="position: sticky; top: 0;">
                                <tr>
                                    <th>SKU</th>
                                    <th>Almacén</th>
                                    <th class="text-right">Cantidad (T)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stock_detalle as $item)
                                    <tr>
                                        <td><code>{{ $item->sku }}</code></td>
                                        <td>{{ $item->almacen_nombre }}</td>
                                        <td class="text-right font-weight-bold">{{ number_format($item->cantidad_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            Sin stock disponible
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Últimas Recepciones --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title text-white">
                            <i class="fas fa-truck-loading mr-2"></i>Últimas Recepciones
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Almacén</th>
                                    <th>Envío</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimas_recepciones as $recepcion)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($recepcion->fecha_recepcion)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $recepcion->almacen_nombre ?? 'N/A' }}</td>
                                        <td><code>{{ $recepcion->codigo_envio ?? 'N/A' }}</code></td>
                                        <td>
                                            @php
                                                $estadoColor = match($recepcion->envio_estado ?? '') {
                                                    'ENTREGADO' => 'success',
                                                    'EN_TRANSITO' => 'info',
                                                    'PENDIENTE' => 'warning',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $estadoColor }}">{{ $recepcion->envio_estado ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Sin recepciones recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Últimos Movimientos --}}
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">
                            <i class="fas fa-exchange-alt mr-2"></i>Últimos Movimientos
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>SKU</th>
                                    <th class="text-right">Cantidad (T)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimos_movimientos as $mov)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($mov->fecha_mov)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @php
                                                $tipoConfig = match($mov->tipo ?? '') {
                                                    'ENTRADA' => ['color' => 'success', 'icon' => 'arrow-down'],
                                                    'SALIDA' => ['color' => 'danger', 'icon' => 'arrow-up'],
                                                    'AJUSTE' => ['color' => 'warning', 'icon' => 'edit'],
                                                    default => ['color' => 'secondary', 'icon' => 'circle']
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $tipoConfig['color'] }}">
                                                <i class="fas fa-{{ $tipoConfig['icon'] }} mr-1"></i>{{ $mov->tipo }}
                                            </span>
                                        </td>
                                        <td><code>{{ $mov->sku }}</code></td>
                                        <td class="text-right">{{ number_format($mov->cantidad_t, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Sin movimientos recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
