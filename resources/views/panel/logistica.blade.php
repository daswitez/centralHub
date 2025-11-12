@extends('layouts.app')

@section('page_title', 'Logística — Envíos & Trazabilidad')

@section('content')
    <div class="row">
        <div class="col-sm-6"><h3 class="mb-0">Logística — Envíos & Trazabilidad</h3><p class="text-secondary mb-0">Planta → Almacén/Cliente</p></div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logística</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Últimos envíos</h3>
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


