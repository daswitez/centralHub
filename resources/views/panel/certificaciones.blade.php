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
    <div class="card card-outline card-dark">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Certificados recientes</h3>
            <span class="badge badge-secondary">Resumen</span>
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
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($certs as $c)
                        <tr>
                            <td class="font-weight-bold">{{ $c->codigo_certificado }}</td>
                            <td>{{ $c->ambito }}</td>
                            <td>{{ $c->area }}</td>
                            <td>{{ $c->vigente_desde }} → {{ $c->vigente_hasta ?? '—' }}</td>
                            <td>{{ $c->emisor }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Sin datos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


