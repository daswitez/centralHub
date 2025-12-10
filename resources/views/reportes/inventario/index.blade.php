@extends('layouts.app')

@section('page_title', 'Reporte: Estado de Inventario')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-boxes mr-2"></i>Estado de Inventario por Almacén</h1>
            <p class="text-secondary mb-0 small">Stock actual, rotación y alertas de inventario</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Estado de Inventario</li>
        </ol>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros del Reporte</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.inventario.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="almacen_id"><i class="fas fa-warehouse mr-1"></i>Almacén</label>
                            <select class="form-control" id="almacen_id" name="almacen_id">
                                <option value="TODOS" {{ $filtros['almacen_id'] === 'TODOS' ? 'selected' : '' }}>Todos los almacenes</option>
                                @foreach($almacenes as $a)
                                    <option value="{{ $a->almacen_id }}" {{ $filtros['almacen_id'] == $a->almacen_id ? 'selected' : '' }}>
                                        {{ $a->nombre }} ({{ $a->codigo_almacen }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nivel_stock"><i class="fas fa-layer-group mr-1"></i>Nivel de Stock</label>
                            <select class="form-control" id="nivel_stock" name="nivel_stock">
                                <option value="TODOS" {{ $filtros['nivel_stock'] === 'TODOS' ? 'selected' : '' }}>Todos los niveles</option>
                                <option value="CRÍTICO" {{ $filtros['nivel_stock'] === 'CRÍTICO' ? 'selected' : '' }}>🔴 Crítico (&lt; 5t)</option>
                                <option value="BAJO" {{ $filtros['nivel_stock'] === 'BAJO' ? 'selected' : '' }}>🟡 Bajo (5-20t)</option>
                                <option value="NORMAL" {{ $filtros['nivel_stock'] === 'NORMAL' ? 'selected' : '' }}>🟢 Normal (20-100t)</option>
                                <option value="ALTO" {{ $filtros['nivel_stock'] === 'ALTO' ? 'selected' : '' }}>🔵 Alto (&gt; 100t)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sku"><i class="fas fa-barcode mr-1"></i>Buscar SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku" 
                                   value="{{ $filtros['sku'] }}" placeholder="Ej: PAPA-CRIOLLA">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-search mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('reportes.inventario.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i> Limpiar
                        </a>
                        <div class="btn-group float-right">
                            <a href="{{ route('reportes.inventario.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <a href="{{ route('reportes.inventario.csv', request()->query()) }}" class="btn btn-success">
                                <i class="fas fa-file-excel mr-1"></i> Exportar CSV
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totales->total_almacenes ?? 0) }}</h3>
                    <p>Almacenes</p>
                </div>
                <div class="icon"><i class="fas fa-warehouse"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totales->total_skus ?? 0) }}</h3>
                    <p>SKUs Distintos</p>
                </div>
                <div class="icon"><i class="fas fa-barcode"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totales->stock_total ?? 0, 1) }}t</h3>
                    <p>Stock Total</p>
                </div>
                <div class="icon"><i class="fas fa-cubes"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totales->items_criticos ?? 0) }}</h3>
                    <p>Items Críticos</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totales->items_bajos ?? 0) }}</h3>
                    <p>Items Stock Bajo</p>
                </div>
                <div class="icon"><i class="fas fa-arrow-down"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Gráfico stock por almacén --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Stock por Almacén</h3>
                </div>
                <div class="card-body">
                    <div id="chart-almacenes" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Movimientos recientes --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>Movimientos Recientes (7 días)</h3>
                </div>
                <div class="card-body p-0" style="max-height: 340px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Almacén</th>
                                <th>SKU</th>
                                <th>Tipo</th>
                                <th class="text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos_recientes as $mov)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($mov->fecha)->format('d/m H:i') }}</td>
                                    <td class="small">{{ $mov->almacen }}</td>
                                    <td><code class="small">{{ $mov->sku }}</code></td>
                                    <td>
                                        <span class="badge badge-{{ $mov->tipo === 'ENTRADA' ? 'success' : 'danger' }}">
                                            {{ $mov->tipo }}
                                        </span>
                                    </td>
                                    <td class="text-right">{{ number_format($mov->cantidad_t, 2) }} t</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Sin movimientos recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de datos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-2"></i>Detalle de Inventario</h3>
            <div class="card-tools">
                <span class="badge badge-warning">{{ count($data) }} items</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Almacén</th>
                            <th>SKU</th>
                            <th class="text-right">Stock Actual</th>
                            <th class="text-right">Entradas (30d)</th>
                            <th class="text-right">Salidas (30d)</th>
                            <th class="text-center">Movimientos</th>
                            <th class="text-center">Días Inv.</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr class="{{ $row->estado_stock === 'CRÍTICO' ? 'table-danger' : ($row->estado_stock === 'BAJO' ? 'table-warning' : '') }}">
                                <td>
                                    <strong>{{ $row->almacen }}</strong>
                                    <small class="text-muted d-block">{{ $row->codigo_almacen }}</small>
                                </td>
                                <td><code>{{ $row->sku }}</code></td>
                                <td class="text-right font-weight-bold">{{ number_format($row->cantidad_actual ?? 0, 2) }} t</td>
                                <td class="text-right text-success">+ {{ number_format($row->entradas_30d ?? 0, 2) }}</td>
                                <td class="text-right text-danger">- {{ number_format($row->salidas_30d ?? 0, 2) }}</td>
                                <td class="text-center">{{ $row->movimientos_30d ?? 0 }}</td>
                                <td class="text-center">
                                    @if(($row->dias_inventario ?? 999) >= 999)
                                        <span class="text-muted">∞</span>
                                    @else
                                        {{ $row->dias_inventario }} días
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $estadoConfig = [
                                            'CRÍTICO' => ['badge' => 'danger', 'icon' => '🔴'],
                                            'BAJO' => ['badge' => 'warning', 'icon' => '🟡'],
                                            'NORMAL' => ['badge' => 'success', 'icon' => '🟢'],
                                            'ALTO' => ['badge' => 'info', 'icon' => '🔵'],
                                        ];
                                        $config = $estadoConfig[$row->estado_stock] ?? $estadoConfig['NORMAL'];
                                    @endphp
                                    <span class="badge badge-{{ $config['badge'] }}">
                                        {{ $config['icon'] }} {{ $row->estado_stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    No hay datos para los filtros seleccionados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ApexCharts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stockPorAlmacen = @json($stock_por_almacen);

            if (stockPorAlmacen.length > 0) {
                const chartAlmacenes = new ApexCharts(document.querySelector('#chart-almacenes'), {
                    series: [{
                        name: 'Stock (t)',
                        data: stockPorAlmacen.map(a => parseFloat(a.stock_total))
                    }],
                    chart: { type: 'bar', height: 300, toolbar: { show: false } },
                    plotOptions: {
                        bar: { horizontal: true, borderRadius: 4 }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toFixed(1) + ' t';
                        }
                    },
                    xaxis: {
                        categories: stockPorAlmacen.map(a => a.almacen),
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(0) + ' t';
                            }
                        }
                    },
                    colors: ['#ffc107'],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2) + ' toneladas';
                            }
                        }
                    }
                });
                chartAlmacenes.render();
            }
        });
    </script>
@endsection
