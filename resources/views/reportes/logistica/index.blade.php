@extends('layouts.app')

@section('page_title', 'Reporte: Análisis Logístico')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-truck mr-2"></i>Análisis Logístico de Entregas</h1>
            <p class="text-secondary mb-0 small">Tiempos de entrega, desempeño de transportistas y eficiencia</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li class="breadcrumb-item active">Análisis Logístico</li>
        </ol>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros del Reporte</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.logistica.index') }}">
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
                            <label for="estado"><i class="fas fa-flag mr-1"></i>Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="TODOS" {{ $filtros['estado'] === 'TODOS' ? 'selected' : '' }}>Todos los estados</option>
                                @foreach($estados as $e)
                                    <option value="{{ $e->estado }}" {{ $filtros['estado'] === $e->estado ? 'selected' : '' }}>
                                        {{ $e->estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transportista_id"><i class="fas fa-user-tie mr-1"></i>Transportista</label>
                            <select class="form-control" id="transportista_id" name="transportista_id">
                                <option value="TODOS" {{ $filtros['transportista_id'] === 'TODOS' ? 'selected' : '' }}>Todos</option>
                                @foreach($transportistas as $t)
                                    <option value="{{ $t->transportista_id }}" {{ $filtros['transportista_id'] == $t->transportista_id ? 'selected' : '' }}>
                                        {{ $t->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-search mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('reportes.logistica.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i> Limpiar
                        </a>
                        <div class="btn-group float-right">
                            <a href="{{ route('reportes.logistica.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
                            </a>
                            <a href="{{ route('reportes.logistica.csv', request()->query()) }}" class="btn btn-success">
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
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totales->total_envios ?? 0) }}</h3>
                    <p>Total Envíos</p>
                </div>
                <div class="icon"><i class="fas fa-shipping-fast"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totales->entregados ?? 0) }}</h3>
                    <p>Entregados</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totales->en_ruta ?? 0) }}</h3>
                    <p>En Ruta</p>
                </div>
                <div class="icon"><i class="fas fa-truck-moving"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totales->toneladas_totales ?? 0, 1) }}t</h3>
                    <p>Toneladas</p>
                </div>
                <div class="icon"><i class="fas fa-weight-hanging"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ number_format($totales->horas_promedio ?? 0, 1) }}h</h3>
                    <p>Tiempo Prom.</p>
                </div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="inner text-white">
                    <h3>{{ number_format($totales->tasa_entrega ?? 0, 1) }}%</h3>
                    <p>Tasa Entrega</p>
                </div>
                <div class="icon"><i class="fas fa-percentage"></i></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Gráfico envíos por estado --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Estado de Envíos</h3>
                </div>
                <div class="card-body">
                    <div id="chart-estados" style="height: 280px;"></div>
                </div>
            </div>
        </div>

        {{-- Gráfico evolución diaria --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Evolución Diaria de Envíos</h3>
                </div>
                <div class="card-body">
                    <div id="chart-evolucion" style="height: 280px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de datos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-2"></i>Desempeño por Transportista</h3>
            <div class="card-tools">
                <span class="badge badge-info">{{ count($data) }} registros</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Transportista</th>
                            <th>Vehículo</th>
                            <th class="text-center">Capacidad</th>
                            <th class="text-center">Envíos</th>
                            <th class="text-center">Entregados</th>
                            <th class="text-center">En Ruta</th>
                            <th class="text-right">Toneladas</th>
                            <th class="text-center">Tiempo Prom.</th>
                            <th class="text-center">Var. Temp.</th>
                            <th class="text-center">Cumplimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="font-weight-bold">{{ $row->transportista }}</td>
                                <td>
                                    @if($row->placa)
                                        <code>{{ $row->placa }}</code>
                                        <small class="text-muted d-block">{{ $row->tipo_vehiculo ?? '' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ number_format($row->capacidad_t ?? 0, 1) }} t</td>
                                <td class="text-center">{{ number_format($row->total_envios) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">{{ $row->entregados ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning">{{ $row->en_ruta ?? 0 }}</span>
                                </td>
                                <td class="text-right">{{ number_format($row->toneladas_totales ?? 0, 2) }}</td>
                                <td class="text-center">{{ number_format($row->horas_promedio_entrega ?? 0, 1) }}h</td>
                                <td class="text-center">
                                    @php $varTemp = $row->variacion_temp_promedio ?? 0; @endphp
                                    <span class="text-{{ $varTemp <= 5 ? 'success' : ($varTemp <= 10 ? 'warning' : 'danger') }}">
                                        ±{{ number_format($varTemp, 1) }}°C
                                    </span>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    @php $tasa = $row->tasa_cumplimiento ?? 0; @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="progress mr-2" style="height: 22px; width: 100px; border-radius: 11px; background: #e9ecef;">
                                            <div class="progress-bar bg-{{ $tasa >= 80 ? 'success' : ($tasa >= 50 ? 'warning' : 'danger') }}" 
                                                 role="progressbar"
                                                 style="width: {{ max($tasa, 5) }}%; border-radius: 11px;">
                                            </div>
                                        </div>
                                        <span class="font-weight-bold text-{{ $tasa >= 80 ? 'success' : ($tasa >= 50 ? 'warning' : 'danger') }}" style="min-width: 45px;">
                                            {{ number_format($tasa, 1) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
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
            const enviosPorEstado = @json($envios_por_estado);
            const evolucionDiaria = @json($evolucion_diaria);

            // Gráfico de estados
            if (enviosPorEstado.length > 0) {
                const coloresEstado = {
                    'ENTREGADO': '#28a745',
                    'EN_RUTA': '#ffc107',
                    'PENDIENTE': '#6c757d',
                    'CANCELADO': '#dc3545'
                };
                
                const chartEstados = new ApexCharts(document.querySelector('#chart-estados'), {
                    series: enviosPorEstado.map(e => parseInt(e.cantidad)),
                    labels: enviosPorEstado.map(e => e.estado),
                    chart: { type: 'donut', height: 280 },
                    colors: enviosPorEstado.map(e => coloresEstado[e.estado] || '#17a2b8'),
                    legend: { position: 'bottom' },
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
                chartEstados.render();
            }

            // Gráfico de evolución
            if (evolucionDiaria.length > 0) {
                const chartEvolucion = new ApexCharts(document.querySelector('#chart-evolucion'), {
                    series: [
                        {
                            name: 'Total Envíos',
                            type: 'column',
                            data: evolucionDiaria.map(e => parseInt(e.total_envios))
                        },
                        {
                            name: 'Entregados',
                            type: 'line',
                            data: evolucionDiaria.map(e => parseInt(e.entregados))
                        }
                    ],
                    chart: { height: 280, type: 'line', toolbar: { show: false } },
                    stroke: { width: [0, 3], curve: 'smooth' },
                    plotOptions: {
                        bar: { columnWidth: '70%', borderRadius: 4 }
                    },
                    xaxis: {
                        categories: evolucionDiaria.map(e => e.fecha_label)
                    },
                    yaxis: {
                        title: { text: 'Cantidad' }
                    },
                    colors: ['#17a2b8', '#28a745'],
                    legend: { position: 'top' }
                });
                chartEvolucion.render();
            }
        });
    </script>
@endsection
