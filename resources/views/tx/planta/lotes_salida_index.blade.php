@extends('layouts.app')

@section('page_title', 'Lotes de Salida')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Lotes de Salida</h3>
                {{-- Botón de registrar removido - Los lotes se gestionan desde el microservicio de Trazabilidad --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>SKU</th>
                                <th>Lote Planta</th>
                                <th>Planta</th>
                                <th>Fecha Empaque</th>
                                <th>Peso (t)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lotes as $lote)
                                <tr>
                                    <td><strong>{{ $lote->codigo_lote_salida }}</strong></td>
                                    <td><span class="badge badge-info">{{ $lote->sku }}</span></td>
                                    <td>{{ $lote->codigo_lote_planta }}</td>
                                    <td>{{ $lote->planta_nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lote->fecha_empaque)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($lote->peso_t, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No hay lotes de salida registrados
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
