@extends('layouts.app')

@section('page_title', 'Lotes de campo')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('components.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form class="form-inline" method="get" action="{{ route('campo.lotes.index') }}">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar código de lote">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('campo.lotes.create') }}" class="btn btn-dark">
                        <i class="fas fa-plus mr-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width: 8rem;">ID</th>
                                <th>Código</th>
                                <th>Productor</th>
                                <th>Variedad</th>
                                <th>Superficie (ha)</th>
                                <th>Siembra</th>
                                <th class="text-center">Sensores</th>
                                <th class="text-center">Trazabilidad</th>
                                <th style="width: 10rem;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($lotes as $l)
                                <tr>
                                    <td>{{ $l->lote_campo_id }}</td>
                                    <td class="font-weight-bold">{{ $l->codigo_lote_campo }}</td>
                                    <td>{{ $l->productor?->nombre ?? '—' }}</td>
                                    <td>{{ $l->variedad?->nombre_comercial ?? '—' }}</td>
                                    <td>{{ $l->superficie_ha }}</td>
                                    <td>{{ $l->fecha_siembra ? \Carbon\Carbon::parse($l->fecha_siembra)->format('d/m/Y') : '—' }}</td>
                                    <td class="text-center">
                                        @if($l->lecturas_count > 0)
                                            <span class="badge badge-success" title="Lecturas de sensores">
                                                <i class="fas fa-microchip"></i> {{ $l->lecturas_count }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($l->procesado_en_planta)
                                            <span class="badge badge-info" title="Procesado en planta">
                                                <i class="fas fa-industry"></i> {{ $l->num_lotes_planta }} lote(s)
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-seedling"></i> En campo
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('campo.lotes.edit', $l->lote_campo_id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline" method="post" action="{{ route('campo.lotes.destroy', $l->lote_campo_id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar lote?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Sin resultados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $lotes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
