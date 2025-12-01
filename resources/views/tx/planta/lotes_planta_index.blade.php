@extends('layouts.app')

@section('page_title', 'Lotes de Planta')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Lotes de Planta</h3>
                <div class="card-tools">
                    <a href="{{ route('tx.planta.lote-planta.form') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Registrar Lote Planta
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Planta</th>
                                <th>Fecha Inicio</th>
                                <th>Lotes Campo</th>
                                <th>Peso Entrada (t)</th>
                                <th>Rendimiento (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lotes as $lote)
                                <tr>
                                    <td><strong>{{ $lote->codigo_lote_planta }}</strong></td>
                                    <td>
                                        <small class="text-muted">{{ $lote->codigo_planta }}</small><br>
                                        {{ $lote->planta_nombre }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($lote->fecha_inicio)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $lote->total_lotes_campo }}</td>
                                    <td>{{ number_format($lote->peso_total_entrada, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $lote->rendimiento_pct >= 75 ? 'success' : ($lote->rendimiento_pct >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($lote->rendimiento_pct, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No hay lotes de planta registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
