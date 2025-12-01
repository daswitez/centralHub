@extends('layouts.app')

@section('page_title', 'Certificaciones y Trazabilidad')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Certificaciones</h1>
            <p class="text-secondary mb-0 small">Certificados por lote y generales por área</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Certificaciones</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-certificate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Certificados vigentes</span>
                    <span class="info-box-number">{{ number_format($kpi_certs_vigentes) }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Por vencer (30 días)</span>
                    <span class="info-box-number">{{ number_format($kpi_certs_por_vencer) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-dark">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Certificados recientes</h3>
            <span class="badge badge-secondary">Con trazabilidad</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Ámbito</th>
                        <th>Área</th>
                        <th>Vigencia</th>
                        <th>Emisor</th>
                        <th>Lotes Campo</th>
                        <th>Lotes Planta</th>
                        <th>Lotes Salida</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($certs as $c)
                        <tr>
                            <td class="font-weight-bold">{{ $c->codigo_certificado }}</td>
                            <td>{{ $c->ambito }}</td>
                            <td>{{ $c->area }}</td>
                            <td class="small">
                                {{ $c->vigente_desde ? \Carbon\Carbon::parse($c->vigente_desde)->format('d/m/Y') : '—' }} → 
                                {{ $c->vigente_hasta ? \Carbon\Carbon::parse($c->vigente_hasta)->format('d/m/Y') : '∞' }}
                            </td>
                            <td>{{ $c->emisor }}</td>
                            <td class="text-center">
                                @if($c->num_lotes_campo > 0)
                                    <span class="badge badge-info">{{ $c->num_lotes_campo }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                @if($c->num_lotes_planta > 0)
                                    <span class="badge badge-info">{{ $c->num_lotes_planta }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                @if($c->num_lotes_salida > 0)
                                    <span class="badge badge-info">{{ $c->num_lotes_salida }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($c->vigente)
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Vigente</span>
                                @else
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Vencido</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">Sin datos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
