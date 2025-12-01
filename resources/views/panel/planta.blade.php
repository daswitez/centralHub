@extends('layouts.app')

@section('page_title', 'Planta — Producción')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Planta</h1>
            <p class="text-secondary mb-0 small">Producción · Lavado · Corte · Escaldado · Fritura · Enfriado · Empaque</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Planta</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-percent"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rendimiento promedio</span>
                    <span class="info-box-number">{{ number_format($kpi_rendimiento_promedio, 1) }}%</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lotes producidos este mes</span>
                    <span class="info-box-number">{{ number_format($kpi_lotes_producidos) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card card-outline card-dark">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-industry mr-2"></i>Batches recientes
                    </h3>
                    <a href="{{ route('cat.plantas.index') }}" class="btn btn-sm btn-outline-dark">Plantas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th>Lote salida</th>
                                <th>Lote planta</th>
                                <th>Planta</th>
                                <th>Fecha</th>
                                <th>Lotes campo</th>
                                <th>Peso (t)</th>
                                <th>Rend. %</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($batches as $b)
                                <tr>
                                    <td class="font-weight-bold">{{ $b->codigo_lote_salida }}</td>
                                    <td>{{ $b->codigo_lote_planta }}</td>
                                    <td>{{ $b->planta ?? '—' }}</td>
                                    <td>{{ $b->fecha_inicio ? \Carbon\Carbon::parse($b->fecha_inicio)->format('d/m/Y') : '—' }}</td>
                                    <td><span class="badge badge-info">{{ $b->num_lotes_campo }}</span></td>
                                    <td>{{ number_format($b->peso_t,3) }}</td>
                                    <td><span class="badge badge-{{ $b->rendimiento_pct >= 85 ? 'success' : ($b->rendimiento_pct >= 70 ? 'warning' : 'danger') }}">{{ $b->rendimiento_pct }}%</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Sin datos</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-clipboard-check mr-2"></i>Control de procesos
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                            <tr>
                                <th>Lote</th>
                                <th>Etapa</th>
                                <th>Fecha</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($control_procesos as $cp)
                                <tr>
                                    <td class="small">{{ $cp->codigo_lote_planta }}</td>
                                    <td class="small">{{ $cp->etapa ?? '—' }}</td>
                                    <td class="small">{{ $cp->fecha_hora ? \Carbon\Carbon::parse($cp->fecha_hora)->format('d/m H:i') : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted small">Sin registros</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
