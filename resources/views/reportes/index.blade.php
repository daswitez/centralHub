@extends('layouts.app')

@section('page_title', 'Centro de Reportes')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-chart-bar mr-2"></i>Centro de Reportes</h1>
            <p class="text-secondary mb-0 small">Reportes analíticos para la toma de decisiones</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reportes</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Reporte 1: Rentabilidad por Cliente --}}
        <div class="col-lg-6 col-xl-3">
            <div class="card card-outline card-primary h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Rentabilidad por Cliente</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Análisis de los clientes más rentables con segmentación por tipo, ingresos, volumen y comparativas de precios.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fas fa-check text-success mr-1"></i> Ranking de clientes</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Comparativa de precios vs mercado</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Filtros por período y tipo</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Gráficos interactivos</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('reportes.rentabilidad.index') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-external-link-alt mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        {{-- Reporte 2: Rendimiento de Plantas --}}
        <div class="col-lg-6 col-xl-3">
            <div class="card card-outline card-success h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-industry mr-2"></i>Rendimiento de Plantas</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Evaluación del desempeño operativo de cada planta de procesamiento, eficiencia y tiempos de producción.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fas fa-check text-success mr-1"></i> Rendimiento por planta</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Eficiencia de conversión</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Variedades procesadas</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Evolución temporal</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('reportes.rendimiento.index') }}" class="btn btn-success btn-block">
                        <i class="fas fa-external-link-alt mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        {{-- Reporte 3: Análisis Logístico --}}
        <div class="col-lg-6 col-xl-3">
            <div class="card card-outline card-info h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-truck mr-2"></i>Análisis Logístico</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Optimización de rutas de distribución, evaluación de transportistas y análisis de tiempos de entrega.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fas fa-check text-success mr-1"></i> Desempeño de transportistas</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Tasa de cumplimiento</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Control de temperatura</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Evolución diaria</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('reportes.logistica.index') }}" class="btn btn-info btn-block">
                        <i class="fas fa-external-link-alt mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>

        {{-- Reporte 4: Estado de Inventario --}}
        <div class="col-lg-6 col-xl-3">
            <div class="card card-outline card-warning h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-boxes mr-2"></i>Estado de Inventario</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Gestión del stock disponible, identificación de productos de baja rotación y alertas de reabastecimiento.
                    </p>
                    <ul class="list-unstyled small text-muted">
                        <li><i class="fas fa-check text-success mr-1"></i> Stock por almacén y SKU</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Alertas de stock crítico</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Días de inventario</li>
                        <li><i class="fas fa-check text-success mr-1"></i> Movimientos recientes</li>
                    </ul>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('reportes.inventario.index') }}" class="btn btn-warning btn-block">
                        <i class="fas fa-external-link-alt mr-1"></i> Ver Reporte
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Información adicional --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Sobre los Reportes</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h6><i class="fas fa-file-pdf text-danger mr-2"></i>Exportación PDF</h6>
                    <p class="small text-muted">Todos los reportes pueden exportarse a PDF para impresión o compartir con stakeholders.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-file-excel text-success mr-2"></i>Exportación CSV</h6>
                    <p class="small text-muted">Exporta los datos en formato CSV compatible con Excel para análisis adicionales.</p>
                </div>
                <div class="col-md-4">
                    <h6><i class="fas fa-filter text-primary mr-2"></i>Filtros Dinámicos</h6>
                    <p class="small text-muted">Cada reporte incluye filtros personalizables para segmentar la información según tus necesidades.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
