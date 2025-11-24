@extends('layouts.app')

@section('page_title', 'Producción de Papa — Bolivia')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Producción de Papa — Bolivia</h1>
            <p class="text-secondary mb-0 small">Siembra → campo → planta → distribución</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Producción</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($kpi_envios_hoy) }}</h3>
                    <p>Envíos de hoy</p>
                </div>
                <div class="icon"><i class="fas fa-truck"></i></div>
                <a href="{{ route('panel.logistica') }}" class="small-box-footer">Ver logística <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($kpi_stock_t, 1) }}<sup class="fs-6"> t</sup></h3>
                    <p>Stock total (inventario)</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="{{ route('panel.logistica') }}" class="small-box-footer">Inventario <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($kpi_lotes_iot) }}</h3>
                    <p>Lotes con sensores activos</p>
                </div>
                <div class="icon"><i class="fas fa-microchip"></i></div>
                <a href="{{ route('campo.lotes.index') }}" class="small-box-footer">Ver lotes <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Waych'a/Desirée</h3>
                    <p>Variedades líderes</p>
                </div>
                <div class="icon"><i class="fas fa-seedling"></i></div>
                <a href="{{ route('cat.variedades.index') }}" class="small-box-footer">Catálogo <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Trazabilidad (últimos lotes)</h3>
                    <span class="text-secondary small">Campo → Planta → Cliente</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Lote salida</th>
                                <th>Lote planta</th>
                                <th>Clientes</th>
                                <th>Peso (t)</th>
                                <th>Rend. %</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($traza_items as $t)
                                <tr>
                                    <td class="font-weight-bold">{{ $t->codigo_lote_salida }}</td>
                                    <td>{{ $t->codigo_lote_planta }}</td>
                                    <td class="text-truncate" title="{{ $t->clientes }}">{{ $t->clientes }}</td>
                                    <td>{{ number_format($t->peso_t,3) }}</td>
                                    <td>{{ $t->rendimiento_pct }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Sin datos</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Inventario por almacén / SKU</h3>
                    <a href="{{ route('cat.almacenes.index') }}" class="btn btn-sm btn-outline-dark">Almacenes</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Almacén</th>
                                <th>SKU</th>
                                <th>Stock (t)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($stock_items as $s)
                                <tr>
                                    <td>{{ $s->codigo_almacen }}</td>
                                    <td>{{ $s->sku }}</td>
                                    <td>{{ number_format($s->stock_t,3) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">Sin datos</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title mb-0">Rendimiento y Proceso (demo)</h3></div>
                <div class="card-body"><div id="revenue-chart"></div></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title mb-0">Trazabilidad por tipo (demo)</h3></div>
                <div class="card-body"><div id="trace-donut" style="height:260px"></div></div>
            </div>
        </div>
    </div>

    {{-- Cargas de librerías para charts (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1"></script>
    <script>
        const salesOptions = {
            series: [
                { name: 'Cosechado (ton)', data: [120, 150, 180, 140, 210, 190, 220] },
                { name: 'Procesado (ton)', data: [95, 130, 160, 130, 195, 175, 205] }
            ],
            chart: { height: 300, type: 'area', toolbar: { show: false } },
            legend: { show: true },
            colors: ['#000', '#6c757d'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: { type: 'datetime', categories: ['2025-01-01','2025-02-01','2025-03-01','2025-04-01','2025-05-01','2025-06-01','2025-07-01'] },
            yaxis: { title: { text: 'Toneladas' } },
            tooltip: { x: { format: 'MMMM yyyy' } }
        };
        new ApexCharts(document.querySelector('#revenue-chart'), salesOptions).render();

        new ApexCharts(document.querySelector('#trace-donut'), {
            series: [52, 31, 17],
            chart: { type: 'donut', height: 260 },
            labels: ['Lote', 'Camión', 'Batch planta'],
            colors: ['#000', '#6c757d', '#adb5bd'],
            dataLabels: { enabled: true },
            legend: { position: 'bottom' }
        }).render();
    </script>
@endsection


