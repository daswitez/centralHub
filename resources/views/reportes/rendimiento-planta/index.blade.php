@extends('layouts.app')

@section('page_title', 'Reporte: Rendimiento de Plantas')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-industry mr-2"></i>Rendimiento y Productividad de Plantas</h1>
            <p class="text-secondary mb-0 small">Eficiencia operativa, tiempos de procesamiento y conversión</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Rendimiento Plantas</li>
        </ol>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros del Reporte</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.rendimiento.index') }}">
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
                            <label for="planta_id"><i class="fas fa-industry mr-1"></i>Planta</label>
                            <select class="form-control" id="planta_id" name="planta_id">
                                <option value="TODAS" {{ $filtros['planta_id'] === 'TODAS' ? 'selected' : '' }}>Todas las plantas</option>
                                @foreach($plantas as $planta)
                                    <option value="{{ $planta->planta_id }}" {{ $filtros['planta_id'] == $planta->planta_id ? 'selected' : '' }}>
                                        {{ $planta->nombre }} ({{ $planta->codigo_planta }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rendimiento_min"><i class="fas fa-percentage mr-1"></i>Rendimiento Mín. (%)</label>
                            <input type="number" class="form-control" id="rendimiento_min" name="rendimiento_min" 
                                   value="{{ $filtros['rendimiento_min'] }}" min="0" max="100" step="1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('reportes.rendimiento.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i> Limpiar
                        </a>
                        <div class="btn-group float-right">
                            <a href="{{ route('reportes.rendimiento.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <a href="{{ route('reportes.rendimiento.csv', request()->query()) }}" class="btn btn-success">
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
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totales->rendimiento_promedio ?? 0, 1) }}%</h3>
                    <p>Rendimiento Promedio</p>
                </div>
                <div class="icon"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totales->total_lotes ?? 0) }}</h3>
                    <p>Lotes Procesados</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totales->total_toneladas_salida ?? 0, 1) }} t</h3>
                    <p>Toneladas Producidas</p>
                </div>
                <div class="icon"><i class="fas fa-weight-hanging"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totales->horas_promedio ?? 0, 1) }}h</h3>
                    <p>Tiempo Prom. Proceso</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Gráfico evolución mensual --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-area mr-2"></i>Evolución del Rendimiento</h3>
                </div>
                <div class="card-body">
                    <div id="chart-evolucion" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        {{-- Variedades procesadas --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-seedling mr-2"></i>Variedades Procesadas</h3>
                </div>
                <div class="card-body">
                    <div id="chart-variedades" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de datos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-2"></i>Detalle por Planta</h3>
            <div class="card-tools">
                <span class="badge badge-success">{{ count($data) }} plantas</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Planta</th>
                            <th>Código</th>
                            <th class="text-center">Lotes</th>
                            <th class="text-center">Rendimiento Prom.</th>
                            <th class="text-center">Rango Rend.</th>
                            <th class="text-right">Entrada (t)</th>
                            <th class="text-right">Salida (t)</th>
                            <th class="text-center">Eficiencia</th>
                            <th class="text-center">Horas Prom.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="font-weight-bold">{{ $row->planta }}</td>
                                <td><code>{{ $row->codigo_planta }}</code></td>
                                <td class="text-center">{{ number_format($row->total_lotes) }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @php 
                                        $rend = $row->rendimiento_promedio;
                                        $badgeClass = $rend >= 80 ? 'success' : ($rend >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="progress mr-2" style="height: 18px; width: 60px; border-radius: 9px; background: #e9ecef;">
                                            <div class="progress-bar bg-{{ $badgeClass }}" style="width: {{ $rend }}%; border-radius: 9px;"></div>
                                        </div>
                                        <span class="font-weight-bold text-{{ $badgeClass }}" style="min-width: 50px;">{{ number_format($rend, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted small">
                                    {{ number_format($row->rendimiento_min ?? 0, 1) }}% - {{ number_format($row->rendimiento_max ?? 0, 1) }}%
                                </td>
                                <td class="text-right">{{ number_format($row->toneladas_entrada ?? 0, 2) }}</td>
                                <td class="text-right">{{ number_format($row->toneladas_salida ?? 0, 2) }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @php $eff = $row->eficiencia_conversion ?? 0; @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="progress mr-2" style="height: 18px; width: 60px; border-radius: 9px; background: #e9ecef;">
                                            <div class="progress-bar bg-{{ $eff >= 85 ? 'success' : ($eff >= 70 ? 'warning' : 'danger') }}" style="width: {{ min($eff, 100) }}%; border-radius: 9px;"></div>
                                        </div>
                                        <span class="font-weight-bold text-{{ $eff >= 85 ? 'success' : ($eff >= 70 ? 'warning' : 'danger') }}" style="min-width: 50px;">{{ number_format($eff, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">{{ number_format($row->horas_promedio ?? 0, 1) }}h</td>
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
            const evolucion = @json($evolucion_mensual);
            const variedades = @json($variedades_procesadas);

            // Gráfico de evolución mejorado
            if (evolucion.length > 0) {
                const chartEvolucion = new ApexCharts(document.querySelector('#chart-evolucion'), {
                    series: [
                        {
                            name: 'Rendimiento (%)',
                            type: 'area',
                            data: evolucion.map(e => parseFloat(e.rendimiento_promedio).toFixed(1))
                        },
                        {
                            name: 'Toneladas Salida',
                            type: 'column',
                            data: evolucion.map(e => parseFloat(e.toneladas_salida).toFixed(2))
                        }
                    ],
                    chart: { 
                        height: 320, 
                        type: 'line', 
                        toolbar: { show: true, tools: { download: true, zoom: true, reset: true } },
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 8,
                            left: 3,
                            blur: 5,
                            opacity: 0.15
                        },
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800
                        }
                    },
                    stroke: { 
                        width: [3, 0], 
                        curve: 'smooth' 
                    },
                    fill: {
                        type: ['gradient', 'solid'],
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.4,
                            opacityFrom: 0.7,
                            opacityTo: 0.2,
                            stops: [0, 100]
                        }
                    },
                    plotOptions: {
                        bar: { 
                            columnWidth: '55%', 
                            borderRadius: 6,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [0],
                        formatter: function(val) {
                            return val + '%';
                        },
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: ['#28a745']
                        },
                        background: {
                            enabled: true,
                            foreColor: '#fff',
                            padding: 4,
                            borderRadius: 4,
                            borderWidth: 0,
                            opacity: 0.9
                        },
                        offsetY: -8
                    },
                    markers: {
                        size: [6, 0],
                        colors: ['#28a745'],
                        strokeWidth: 2,
                        strokeColors: '#fff',
                        hover: { size: 9 }
                    },
                    xaxis: {
                        categories: evolucion.map(e => e.mes_label),
                        labels: {
                            style: { fontSize: '12px', fontWeight: 500 }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: [
                        {
                            title: { 
                                text: 'Rendimiento (%)',
                                style: { fontSize: '13px', fontWeight: 600, color: '#28a745' }
                            },
                            min: 0,
                            max: 100,
                            labels: {
                                formatter: function(val) { return val.toFixed(0) + '%'; },
                                style: { colors: '#28a745' }
                            }
                        },
                        {
                            opposite: true,
                            title: { 
                                text: 'Toneladas',
                                style: { fontSize: '13px', fontWeight: 600, color: '#007bff' }
                            },
                            labels: {
                                formatter: function(val) { return val.toFixed(1) + ' t'; },
                                style: { colors: '#007bff' }
                            }
                        }
                    ],
                    colors: ['#28a745', '#007bff'],
                    legend: { 
                        position: 'top',
                        horizontalAlign: 'center',
                        fontSize: '13px',
                        fontWeight: 500,
                        markers: { width: 12, height: 12, radius: 3 }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(val, { seriesIndex }) {
                                return seriesIndex === 0 ? val + '%' : val + ' toneladas';
                            }
                        }
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        strokeDashArray: 4,
                        padding: { left: 10, right: 10 }
                    }
                });
                chartEvolucion.render();
            } else {
                document.querySelector('#chart-evolucion').innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-chart-line fa-3x mb-3"></i><p>No hay datos de evolución para mostrar</p></div>';
            }

            // Gráfico de variedades mejorado
            if (variedades.length > 0) {
                const chartVariedades = new ApexCharts(document.querySelector('#chart-variedades'), {
                    series: variedades.map(v => parseFloat(v.toneladas)),
                    labels: variedades.map(v => v.variedad || 'Sin especificar'),
                    chart: { 
                        type: 'donut', 
                        height: 320,
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 3,
                            left: 0,
                            blur: 6,
                            opacity: 0.1
                        }
                    },
                    colors: ['#28a745', '#20c997', '#17a2b8', '#6f42c1', '#fd7e14', '#e83e8c'],
                    legend: { 
                        position: 'bottom',
                        fontSize: '12px',
                        markers: { width: 10, height: 10, radius: 2 }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        offsetY: -5
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '22px',
                                        fontWeight: 700,
                                        offsetY: 5,
                                        formatter: function(val) {
                                            return parseFloat(val).toFixed(1) + ' t';
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '14px',
                                        fontWeight: 500,
                                        color: '#6c757d',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(1) + ' t';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    stroke: { width: 2, colors: ['#fff'] },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toFixed(1) + '%';
                        },
                        style: { fontSize: '11px', fontWeight: 600 },
                        dropShadow: { enabled: false }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toFixed(2) + ' toneladas';
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: { height: 280 },
                            legend: { position: 'bottom', fontSize: '10px' }
                        }
                    }]
                });
                chartVariedades.render();
            } else {
                document.querySelector('#chart-variedades').innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-seedling fa-3x mb-3"></i><p>No hay datos de variedades</p></div>';
            }
        });
    </script>
@endsection
