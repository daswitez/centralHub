@extends('layouts.app')

@section('page_title', 'Departamentos')

@section('content')
    {{-- Filtros / Acciones --}}
    <div class="row">
        <div class="col-12">
            @include('components.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form class="form-inline" method="get" action="{{ route('cat.departamentos.index') }}">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar nombre"
                                   aria-label="Buscar">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="submit" aria-label="Buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('cat.departamentos.create') }}" class="btn btn-dark" aria-label="Nuevo">
                        <i class="fas fa-plus mr-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width: 8rem;">ID</th>
                                <th>Nombre</th>
                                <th style="width: 10rem;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($departamentos as $d)
                                <tr>
                                    <td>{{ $d->departamento_id }}</td>
                                    <td>{{ $d->nombre }}</td>
                                    <td>
                                        <a href="{{ route('cat.departamentos.edit', $d->departamento_id) }}" class="btn btn-sm btn-outline-dark" aria-label="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline" method="post" action="{{ route('cat.departamentos.destroy', $d->departamento_id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar" onclick="return confirm('¿Eliminar departamento?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Sin resultados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $departamentos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


