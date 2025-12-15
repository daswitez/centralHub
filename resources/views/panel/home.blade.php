@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

    {{-- Fila de Info Boxes con animación --}}
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 bg-info animate__animated animate__fadeInUp">
                <span class="info-box-icon"><i class="fas fa-warehouse"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Stock en Almacenes</span>
                    <span class="info-box-number">{{ number_format($kpi_stock_t, 1) }} <small>toneladas</small></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 bg-success animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Envíos Hoy</span>
                    <span class="info-box-number">{{ $kpi_envios_hoy }}</span>
                    <span class="info-box-text"><small>{{ $kpi_envios_en_ruta }} en ruta</small></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 bg-warning animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Órdenes Pendientes</span>
                    <span class="info-box-number">{{ $kpi_ordenes_pendientes }}</span>
                    <span class="info-box-text"><small>por despachar</small></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 bg-danger animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <span class="info-box-icon"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lotes Procesados</span>
                    <span class="info-box-number">{{ $kpi_lotes_mes }}</span>
                    <span class="info-box-text"><small>este mes</small></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Segunda fila de Small Boxes --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3>{{ number_format($kpi_toneladas_empacadas, 1) }}<sup style="font-size: 20px">t</sup></h3>
                    <p>Toneladas Empacadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dolly"></i>
                </div>
                <a href="{{ route('tx.planta.lotes-salida.index') }}" class="small-box-footer">
                    Ver lotes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{ $kpi_productores }}</h3>
                    <p>Productores Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('campo.productores.index') }}" class="small-box-footer">
                    Ver productores <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{ $kpi_pedidos_mes }}</h3>
                    <p>Pedidos este Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('comercial.pedidos.index') }}" class="small-box-footer">
                    Ver pedidos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3>{{ $kpi_vehiculos_disponibles }}</h3>
                    <p>Vehículos Disponibles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <a href="{{ route('vehiculos.index') }}" class="small-box-footer">
                    Ver flota <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        {{-- Gráfico de Envíos por Estado --}}
        <div class="col-lg-6">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-truck mr-1"></i>
                        Estado de Envíos
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="enviosEstadoChart"
                        style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico de Producción Mensual --}}
        <div class="col-lg-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Producción Mensual (Últimos 6 meses)
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="produccionMensualChart"
                        style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>


    {{-- Tablas de Información --}}
    <div class="row">
        {{-- Resumen de Ventas del Mes --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-dollar-sign text-success mr-2"></i>Ventas del Mes</h3>
                    <div class="card-tools">
                        <a href="{{ route('comercial.pedidos.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    {{-- Resumen de totales del mes --}}
                    <div class="d-flex justify-content-around text-center border-bottom py-2 bg-light">
                        <div>
                            <span class="text-muted small">Pedidos</span>
                            <h5 class="mb-0 text-primary">{{ $ventas_mes_totales->total_pedidos ?? 0 }}</h5>
                        </div>
                        <div>
                            <span class="text-muted small">Toneladas</span>
                            <h5 class="mb-0 text-info">{{ number_format($ventas_mes_totales->total_toneladas ?? 0, 1) }} t
                            </h5>
                        </div>
                        <div>
                            <span class="text-muted small">Ingresos</span>
                            <h5 class="mb-0 text-success">${{ number_format($ventas_mes_totales->total_usd ?? 0, 0) }}</h5>
                        </div>
                    </div>
                    {{-- Top clientes --}}
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th class="text-center">Pedidos</th>
                                <th class="text-right">Toneladas</th>
                                <th class="text-right">Total USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventas_por_cliente as $v)
                                <tr>
                                    <td>
                                        <strong>{{ $v->cliente }}</strong>
                                        <br>
                                        @php
                                            $badgeColor = match ($v->tipo) {
                                                'MAYORISTA' => 'bg-primary',
                                                'RETAIL' => 'bg-success',
                                                'PROCESADOR' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">{{ $v->tipo }}</span>
                                    </td>
                                    <td class="text-center">{{ $v->num_pedidos }}</td>
                                    <td class="text-right">{{ number_format($v->toneladas_vendidas, 2) }} t</td>
                                    <td class="text-right"><strong>${{ number_format($v->total_usd, 0) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Sin ventas este mes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Últimos Envíos --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-truck text-success mr-2"></i>Últimos Envíos</h3>
                    <div class="card-tools">
                        <a href="{{ route('panel.logistica') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Conductor</th>
                                <th>Toneladas</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimos_envios as $e)
                                <tr>
                                    <td>
                                        <strong>{{ $e->codigo_envio }}</strong>
                                        @if($e->vehiculo)
                                            <br><small class="text-muted">{{ $e->vehiculo }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $e->transportista ?? 'Sin asignar' }}</td>
                                    <td>{{ number_format($e->toneladas, 2) }} t</td>
                                    <td>
                                        @php
                                            $badgeClass = match ($e->estado) {
                                                'EN_RUTA' => 'bg-primary',
                                                'ENTREGADO' => 'bg-success',
                                                'PENDIENTE' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $e->estado }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Sin envíos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Órdenes y Lotes --}}
    <div class="row">
        {{-- Órdenes de Envío Recientes --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-clipboard-list text-warning mr-2"></i>Órdenes de Envío</h3>
                    <div class="card-tools">
                        <a href="{{ route('logistica.envios.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($ultimas_ordenes as $o)
                            <li class="item">
                                <div class="product-img">
                                    @php
                                        $iconClass = match ($o->estado) {
                                            'ENTREGADO' => 'bg-success',
                                            'EN_RUTA' => 'bg-primary',
                                            'CONDUCTOR_ASIGNADO' => 'bg-info',
                                            'PENDIENTE' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="btn btn-sm {{ $iconClass }} rounded-circle">
                                        <i class="fas fa-shipping-fast"></i>
                                    </span>
                                </div>
                                <div class="product-info">
                                    <span class="product-description">
                                        {{ $o->planta ?? 'N/A' }} → {{ $o->almacen ?? 'N/A' }}
                                        @if($o->conductor)
                                            | <i class="fas fa-user"></i> {{ $o->conductor }}
                                        @endif
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="item text-center text-muted py-3">Sin órdenes recientes</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Últimos Lotes Empacados --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-box text-danger mr-2"></i>Últimos Lotes Empacados</h3>
                    <div class="card-tools">
                        <a href="{{ route('tx.planta.lotes-salida.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($ultimos_lotes as $l)
                            <li class="item">
                                <div class="product-img">
                                    <span class="btn btn-sm bg-gradient-primary rounded-circle">
                                        <i class="fas fa-box"></i>
                                    </span>
                                </div>
                                <div class="product-info">
                                    <span class="product-title">
                                        {{ $l->codigo_lote_salida }}
                                        <span class="badge badge-info float-right">{{ number_format($l->peso_t, 2) }} t</span>
                                    </span>
                                    <span class="product-description">
                                        <span class="badge bg-secondary">{{ $l->sku }}</span>
                                        | {{ $l->planta }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="item text-center text-muted py-3">Sin lotes recientes</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('extra.recurso1') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-search"></i> Ver Trazabilidad
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Acceso Rápido --}}
    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient-dark">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Accesos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-4 mb-3">
                            <a href="{{ route('extra.recurso1') }}" class="btn btn-app bg-success">
                                <i class="fas fa-route"></i> Trazabilidad
                            </a>
                        </div>
                        <div class="col-md-2 col-4 mb-3">
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-app bg-info">
                                <i class="fas fa-truck"></i> Vehículos
                            </a>
                        </div>
                        <div class="col-md-2 col-4 mb-3">
                            <a href="{{ route('cat.almacenes.index') }}" class="btn btn-app bg-warning">
                                <i class="fas fa-warehouse"></i> Almacenes
                            </a>
                        </div>
                        <div class="col-md-2 col-4 mb-3">
                            <a href="{{ route('comercial.pedidos.index') }}" class="btn btn-app bg-danger">
                                <i class="fas fa-shopping-cart"></i> Pedidos
                            </a>
                        </div>
                        <div class="col-md-2 col-4 mb-3">
                            <a href="{{ route('panel.planta') }}" class="btn btn-app bg-secondary">
                                <i class="fas fa-industry"></i> Planta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Animate.css CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gráfico de Envíos por Estado (Donut)
            const enviosData = @json($envios_por_estado);
            const enviosLabels = enviosData.map(e => {
                // Hacer los labels más legibles
                const labels = {
                    'PENDIENTE': 'Pendiente',
                    'EN_RUTA': 'En Ruta',
                    'ENTREGADO': 'Entregado',
                    'PROGRAMADO': 'Programado',
                    'CANCELADO': 'Cancelado'
                };
                return labels[e.estado] || e.estado;
            });
            const enviosValues = enviosData.map(e => parseInt(e.cantidad));

            // Colores por estado
            const estadoColores = {
                'Pendiente': '#ffc107',
                'En Ruta': '#007bff',
                'Entregado': '#28a745',
                'Programado': '#17a2b8',
                'Cancelado': '#dc3545'
            };
            const enviosColores = enviosLabels.map(l => estadoColores[l] || '#6c757d');

            new Chart(document.getElementById('enviosEstadoChart'), {
                type: 'doughnut',
                data: {
                    labels: enviosLabels.length > 0 ? enviosLabels : ['Sin envíos'],
                    datasets: [{
                        data: enviosValues.length > 0 ? enviosValues : [1],
                        backgroundColor: enviosColores.length > 0 ? enviosColores : ['#6c757d'],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Total: ' + enviosValues.reduce((a, b) => a + b, 0) + ' envíos',
                            font: { size: 14, weight: 'normal' },
                            padding: { bottom: 10 }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Gráfico de Producción Mensual (Barras Agrupadas)
            const produccionData = @json($produccion_mensual);
            const produccionLabels = produccionData.map(p => p.mes);
            const produccionLotes = produccionData.map(p => parseInt(p.lotes));
            const produccionToneladas = produccionData.map(p => parseFloat(p.toneladas) || 0);

            new Chart(document.getElementById('produccionMensualChart'), {
                type: 'bar',
                data: {
                    labels: produccionLabels.length > 0 ? produccionLabels : ['Sin datos'],
                    datasets: [{
                        label: 'Lotes Procesados',
                        data: produccionLotes.length > 0 ? produccionLotes : [0],
                        backgroundColor: '#007bff',
                        borderColor: '#0056b3',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.8
                    }, {
                        label: 'Toneladas Empacadas',
                        data: produccionToneladas.length > 0 ? produccionToneladas : [0],
                        backgroundColor: '#28a745',
                        borderColor: '#1e7e34',
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12 }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });

        });
    </script>


    <style>
        /* Animaciones suaves en hover */
        .info-box,
        .small-box {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-box:hover,
        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Botones de app */
        .btn-app {
            transition: transform 0.2s ease;
        }

        .btn-app:hover {
            transform: scale(1.05);
        }

        /* Productos list hover */
        .products-list .item {
            transition: background-color 0.2s ease;
        }

        .products-list .item:hover {
            background-color: #f8f9fa;
        }

        /* Progress bars animados */
        .progress-bar {
            transition: width 1s ease-in-out;
        }

        /* Card headers */
        .card-header h3.card-title {
            font-weight: 600;
        }
    </style>
@endsection