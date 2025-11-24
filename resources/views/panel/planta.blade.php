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
                        <th>Peso (t)</th>
                        <th>Rend. %</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($batches as $b)
                        <tr>
                            <td class="font-weight-bold">{{ $b->codigo_lote_salida }}</td>
                            <td>{{ $b->codigo_lote_planta }}</td>
                            <td>{{ number_format($b->peso_t,3) }}</td>
                            <td>{{ $b->rendimiento_pct }}</td>
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


