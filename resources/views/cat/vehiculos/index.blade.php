@extends('layouts.app')

@section('page_title', 'Vehículos')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Flota de Vehículos</h1>
            <p class="text-muted mb-0">Gestión de vehículos de transporte</p>
        </div>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Vehículo
        </a>
    </div>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        @foreach($estados as $e)
                            <option value="{{ $e }}" {{ $estado === $e ? 'selected' : '' }}>{{ $e }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        @foreach($tipos as $t)
                            <option value="{{ $t }}" {{ $tipo === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Vehículos --}}
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Placa</th>
                        <th>Marca/Modelo</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Conductor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $v)
                        <tr>
                            <td><strong>{{ $v->codigo_vehiculo }}</strong></td>
                            <td>
                                <span class="badge badge-secondary">{{ $v->placa }}</span>
                            </td>
                            <td>{{ $v->marca }} {{ $v->modelo }}</td>
                            <td>
                                <span class="badge badge-{{ $v->tipo === 'REFRIGERADO' ? 'info' : 'secondary' }}">
                                    {{ $v->tipo }}
                                </span>
                            </td>
                            <td>{{ number_format($v->capacidad_t, 1) }} t</td>
                            <td>
                                @if($v->conductor_nombre)
                                    <i class="fas fa-user text-success"></i> {{ $v->conductor_nombre }}
                                @else
                                    <span class="text-muted">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $colorEstado = match($v->estado) {
                                        'DISPONIBLE' => 'success',
                                        'EN_USO' => 'primary',
                                        'MANTENIMIENTO' => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge badge-{{ $colorEstado }}">{{ $v->estado }}</span>
                            </td>
                            <td>
                                <a href="{{ route('vehiculos.show', $v->vehiculo_id) }}" 
                                   class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('vehiculos.edit', $v->vehiculo_id) }}" 
                                   class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No hay vehículos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vehiculos->hasPages())
            <div class="card-footer">
                {{ $vehiculos->links() }}
            </div>
        @endif
    </div>
@endsection
