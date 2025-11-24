@extends('layouts.app')

@section('page_title', 'Logística — Envíos & Trazabilidad')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Logística</h1>
            <p class="text-secondary mb-0 small">Envíos · Planta → Almacén / Cliente · Trazabilidad en frío</p>
        </div>
        <ol class="breadcrumb float-sm-right bg-transparent mb-0 p-0">
            <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Logística</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="card card-outline card-dark">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-route mr-2"></i>Últimos envíos
            </h3>
            <a href="{{ route('cat.transportistas.index') }}" class="btn btn-sm btn-outline-dark">Transportistas</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Salida</th>
                        <th>Llegada</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($envios as $e)
                        <tr>
                            <td class="font-weight-bold">{{ $e->codigo_envio }}</td>
                            <td>{{ $e->fecha_salida }}</td>
                            <td>{{ $e->fecha_llegada ?? '—' }}</td>
                            <td><span class="badge badge-{{ $e->estado === 'ENTREGADO' ? 'success' : ($e->estado === 'EN_RUTA' ? 'warning' : 'secondary') }}">{{ $e->estado }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Sin datos</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


