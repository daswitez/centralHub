@extends('layouts.app')

@section('page_title', 'Certificaciones y Trazabilidad')

@section('content')
    <div class="row">
        <div class="col-sm-7">
            <h3 class="mb-0">Certificaciones de Calidad y Trazabilidad</h3>
            <p class="text-secondary mb-0">Certificados por Lote y Generales por Área</p>
        </div>
        <div class="col-sm-5">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Certificaciones</li>
            </ol>
        </div>
    </div>

    <div class="card">
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


