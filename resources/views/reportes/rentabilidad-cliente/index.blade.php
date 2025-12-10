@extends('layouts.app')

@section('page_title', 'Reporte: Rentabilidad por Cliente')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-chart-pie mr-2"></i>Análisis de Rentabilidad por Cliente</h1>
            <p class="text-secondary mb-0 small">Segmentación, ingresos y métricas comparativas</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Rentabilidad por Cliente</li>
        </ol>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros del Reporte</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.rentabilidad.index') }}" id="filtros-form">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_inicio"><i class="far fa-calendar-alt mr-1"></i>Fecha Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                                   value="{{ $filtros['fecha_inicio'] }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fecha_fin"><i class="far fa-calendar-alt mr-1"></i>Fecha Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                                   value="{{ $filtros['fecha_fin'] }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_cliente"><i class="fas fa-users mr-1"></i>Tipo de Cliente</label>
                            <select class="form-control" id="tipo_cliente" name="tipo_cliente">
                                <option value="TODOS" {{ $filtros['tipo_cliente'] === 'TODOS' ? 'selected' : '' }}>Todos los tipos</option>
                                @foreach($tipos_cliente as $tipo)
                                    <option value="{{ $tipo->tipo }}" {{ $filtros['tipo_cliente'] === $tipo->tipo ? 'selected' : '' }}>
                                        {{ $tipo->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="top_n"><i class="fas fa-sort-amount-down mr-1"></i>Top N Clientes</label>
                            <select class="form-control" id="top_n" name="top_n">
                                <option value="5" {{ $filtros['top_n'] == 5 ? 'selected' : '' }}>Top 5</option>
                                <option value="10" {{ $filtros['top_n'] == 10 ? 'selected' : '' }}>Top 10</option>
                                <option value="20" {{ $filtros['top_n'] == 20 ? 'selected' : '' }}>Top 20</option>
                                <option value="50" {{ $filtros['top_n'] == 50 ? 'selected' : '' }}>Top 50</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('reportes.rentabilidad.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i> Limpiar
                        </a>
                        <div class="btn-group float-right">
                            <a href="{{ route('reportes.rentabilidad.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <a href="{{ route('reportes.rentabilidad.csv', request()->query()) }}" class="btn btn-success">
                                <i class="fas fa-file-excel mr-1"></i> Exportar CSV
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- KPIs Resumen --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totales->total_clientes ?? 0) }}</h3>
                    <p>Clientes Activos</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>$ {{ number_format($totales->total_ingresos ?? 0, 0) }}</h3>
                    <p>Ingresos Totales (USD)</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totales->total_toneladas ?? 0, 1) }} t</h3>
                    <p>Toneladas Vendidas</p>
                </div>
                <div class="icon"><i class="fas fa-weight-hanging"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>$ {{ number_format($totales->precio_promedio ?? 0, 2) }}</h3>
                    <p>Precio Prom. (USD/t)</p>
                </div>
                <div class="icon"><i class="fas fa-tags"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Gráfico de Ventas por Tipo --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Distribución por Tipo de Cliente</h3>
                </div>
                <div class="card-body">
                    <div id="chart-tipo-cliente" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Gráfico de Barras - Top Clientes --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Top Clientes por Ingresos</h3>
                </div>
                <div class="card-body">
                    <div id="chart-top-clientes" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de datos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-2"></i>Detalle de Clientes</h3>
            <div class="card-tools">
                <span class="badge badge-primary">{{ count($data) }} resultados</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Ubicación</th>
                            <th class="text-center">Pedidos</th>
                            <th class="text-right">Toneladas</th>
                            <th class="text-right">Total USD</th>
                            <th class="text-right">Precio Prom.</th>
                            <th class="text-center">vs Mercado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $row)
                            <tr>
                                <td class="text-center">
                                    @if($index < 3)
                                        <span class="badge badge-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }}">
                                            <i class="fas fa-trophy"></i> {{ $index + 1 }}
                                        </span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ $row->nombre }}</td>
                                <td>
                                    <span class="badge badge-{{ $row->tipo === 'MAYORISTA' ? 'primary' : ($row->tipo === 'RETAIL' ? 'success' : 'info') }}">
                                        {{ $row->tipo }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $row->municipio ?? '-' }}{{ $row->departamento ? ', ' . $row->departamento : '' }}
                                </td>
                                <td class="text-center">{{ number_format($row->num_pedidos) }}</td>
                                <td class="text-right">{{ number_format($row->total_toneladas, 2) }}</td>
                                <td class="text-right font-weight-bold">$ {{ number_format($row->total_ingresos, 2) }}</td>
                                <td class="text-right">$ {{ number_format($row->precio_promedio, 2) }}</td>
                                <td class="text-center">
                                    @php $diff = $row->diferencia_precio ?? 0; @endphp
                                    @if($diff > 0)
                                        <span class="text-success"><i class="fas fa-arrow-up"></i> +{{ number_format($diff, 2) }}</span>
                                    @elseif($diff < 0)
                                        <span class="text-danger"><i class="fas fa-arrow-down"></i> {{ number_format($diff, 2) }}</span>
                                    @else
                                        <span class="text-muted">= 0.00</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
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
            // Datos para gráficos
            const ventasPorTipo = @json($ventas_por_tipo);
            const dataClientes = @json($data);

            // Gráfico de dona - Distribución por tipo
            if (ventasPorTipo.length > 0) {
                const chartTipo = new ApexCharts(document.querySelector('#chart-tipo-cliente'), {
                    series: ventasPorTipo.map(v => parseFloat(v.total_ingresos)),
                    labels: ventasPorTipo.map(v => v.tipo),
                    chart: { type: 'donut', height: 300 },
                    colors: ['#007bff', '#28a745', '#17a2b8', '#ffc107'],
                    legend: { position: 'bottom' },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total USD',
                                        formatter: function(w) {
                                            return '$ ' + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString('es', {minimumFractionDigits: 0});
                                        }
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return '$ ' + val.toLocaleString('es', {minimumFractionDigits: 2});
                            }
                        }
                    }
                });
                chartTipo.render();
            }

            // Gráfico de barras horizontales - Top clientes
            if (dataClientes.length > 0) {
                const topClientes = dataClientes.slice(0, 10);
                const chartBarras = new ApexCharts(document.querySelector('#chart-top-clientes'), {
                    series: [{
                        name: 'Ingresos USD',
                        data: topClientes.map(c => parseFloat(c.total_ingresos))
                    }],
                    chart: { type: 'bar', height: 300, toolbar: { show: false } },
                    plotOptions: {
                        bar: { horizontal: true, borderRadius: 4 }
                    },
                    dataLabels: { 
                        enabled: true,
                        formatter: function(val) {
                            return '$ ' + val.toLocaleString('es', {minimumFractionDigits: 0});
                        }
                    },
                    xaxis: {
                        categories: topClientes.map(c => c.nombre.substring(0, 20)),
                        labels: {
                            formatter: function(val) {
                                return '$ ' + val.toLocaleString('es', {minimumFractionDigits: 0});
                            }
                        }
                    },
                    colors: ['#007bff'],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return '$ ' + val.toLocaleString('es', {minimumFractionDigits: 2});
                            }
                        }
                    }
                });
                chartBarras.render();
            }
        });
    </script>
@endsection
