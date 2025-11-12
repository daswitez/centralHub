@extends('layouts.app')

@section('page_title', 'Municipios')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('components.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form class="form-inline" method="get" action="{{ route('cat.municipios.index') }}">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <select name="departamento_id" class="form-control">
                                    <option value="0">Todos los departamentos</option>
                                    @foreach ($departamentos as $dep)
                                        <option value="{{ $dep->departamento_id }}" {{ $departamentoId == $dep->departamento_id ? 'selected' : '' }}>
                                            {{ $dep->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar nombre">
                                    <div class="input-group-append">
                                        <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('cat.municipios.create') }}" class="btn btn-dark">
                        <i class="fas fa-plus mr-1"></i> Nuevo
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-dark">
                            <tr>
                                <th style="width: 8rem;">ID</th>
                                <th>Departamento</th>
                                <th>Nombre</th>
                                <th style="width: 10rem;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($municipios as $m)
                                <tr>
                                    <td>{{ $m->municipio_id }}</td>
                                    <td>{{ $m->departamento?->nombre }}</td>
                                    <td>{{ $m->nombre }}</td>
                                    <td>
                                        <a href="{{ route('cat.municipios.edit', $m->municipio_id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline" method="post" action="{{ route('cat.municipios.destroy', $m->municipio_id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar municipio?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Sin resultados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $municipios->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


