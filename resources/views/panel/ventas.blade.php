@extends('layouts.app')

@section('page_title', 'Ventas — Panel Comercial')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Ventas</h1>
            <p class="text-secondary mb-0 small">Pedidos, ingresos, canales y márgenes</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ventas</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pedidos de hoy</span>
                    <span class="info-box-number">{{ number_format($kpi_pedidos_hoy) }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ingresos del mes</span>
                    <span class="info-box-number">$ {{ number_format($kpi_ingresos_mes, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-bag-shopping"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pedidos cerrados</span>
                    <span class="info-box-number">{{ number_format($kpi_pedidos_cerrados) }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Precio prom. por ton</span>
                    <span class="info-box-number">$ {{ number_format($kpi_precio_promedio, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Últimos Pedidos</h5>
                    <span class="badge badge-secondary">Tiempo real</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pedidos as $p)
                                <tr>
                                    <td class="font-weight-bold">{{ $p->codigo_pedido }}</td>
                                    <td>{{ $p->cliente }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->fecha_pedido)->format('d/m/Y') }}</td>
                                    <td>{{ $p->num_items }}</td>
                                    <td>$ {{ number_format($p->total, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $p->estado === 'COMPLETADO' ? 'success' : ($p->estado === 'PENDIENTE' ? 'warning' : 'secondary') }}">
                                            {{ $p->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Sin pedidos</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Ventas por Canal</h5></div>
        <div class="card-body">
            <div id="sales-chart"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1"></script>
    <script>
        const ventasData = @json($ventas_por_mes);
        
        const categorias = ventasData.map(v => v.mes);
        const mayorista = ventasData.map(v => parseFloat(v.mayorista || 0));
        const retail = ventasData.map(v => parseFloat(v.retail || 0));
        const procesador = ventasData.map(v => parseFloat(v.procesador || 0));
        
        const salesChart = new ApexCharts(document.querySelector('#sales-chart'), {
            series: [
                { name: 'Mayorista ($)', data: mayorista },
                { name: 'Retail ($)', data: retail },
                { name: 'Procesador ($)', data: procesador }
            ],
            chart: { height: 300, type: 'area', toolbar: { show: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: { 
                type: 'datetime', 
                categories: categorias,
                labels: {
                    format: 'MMM yyyy'
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return '$ ' + val.toFixed(0);
                    }
                }
            },
            colors: ['#000','#6c757d','#adb5bd'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return '$ ' + val.toFixed(2);
                    }
                }
            }
        });
        salesChart.render();
    </script>
@endsection
