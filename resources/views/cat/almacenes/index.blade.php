@extends('layouts.app')

@section('page_title', 'Almacenes')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('components.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form class="form-inline" method="get" action="{{ route('cat.almacenes.index') }}">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar código o nombre">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('cat.almacenes.create') }}" class="btn btn-dark">
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
                                <th>Nombre</th>
                                <th>Municipio</th>
                                <th style="width: 10rem;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($almacenes as $a)
                                <tr>
                                    <td>{{ $a->almacen_id }}</td>
                                    <td>{{ $a->codigo_almacen }}</td>
                                    <td>{{ $a->nombre }}</td>
                                    <td>{{ $a->municipio?->nombre }}</td>
                                    <td>
                                        <a href="{{ route('cat.almacenes.edit', $a->almacen_id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline" method="post" action="{{ route('cat.almacenes.destroy', $a->almacen_id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar almacén?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Sin resultados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $almacenes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


