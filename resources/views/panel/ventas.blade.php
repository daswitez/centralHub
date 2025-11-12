@extends('layouts.app')

@section('page_title', 'Ventas — Panel Comercial')

@section('content')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Ventas — Panel Comercial</h3><p class="text-secondary mb-0">Pedidos, ingresos, canales y márgenes</p></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ventas</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ingresos del mes</span>
                    <span class="info-box-number">$ 128,450</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-bag-shopping"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pedidos cerrados</span>
                    <span class="info-box-number">762</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Precio prom. por ton</span>
                    <span class="info-box-number">$ 245</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Cobranzas vencidas</span>
                    <span class="info-box-number">$ 12,930</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Reporte Comercial Mensual</h5></div>
        <div class="card-body">
            <div id="sales-chart"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1"></script>
    <script>
        const salesChart = new ApexCharts(document.querySelector('#sales-chart'), {
            series: [
                { name: 'Mayorista (ton)', data: [120, 140, 160, 150, 190, 210, 230] },
                { name: 'Retail (ton)', data: [60, 72, 85, 79, 92, 101, 115] },
                { name: 'Procesados (ton)', data: [25, 30, 35, 33, 38, 42, 50] }
            ],
            chart: { height: 260, type: 'area', toolbar: { show: false } },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: { type: 'datetime', categories: ['2025-01-01','2025-02-01','2025-03-01','2025-04-01','2025-05-01','2025-06-01','2025-07-01'] },
            colors: ['#000','#6c757d','#adb5bd']
        });
        salesChart.render();
    </script>
@endsection


