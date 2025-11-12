@extends('layouts.app')

@section('page_title', 'Clientes')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('components.alerts')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <form class="form-inline" method="get" action="{{ route('cat.clientes.index') }}">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar código/nombre/tipo">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('cat.clientes.create') }}" class="btn btn-dark">
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
                                <th>Tipo</th>
                                <th>Municipio</th>
                                <th style="width: 10rem;">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($clientes as $c)
                                <tr>
                                    <td>{{ $c->cliente_id }}</td>
                                    <td>{{ $c->codigo_cliente }}</td>
                                    <td>{{ $c->nombre }}</td>
                                    <td>{{ $c->tipo }}</td>
                                    <td>{{ $c->municipio?->nombre }}</td>
                                    <td>
                                        <a href="{{ route('cat.clientes.edit', $c->cliente_id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="d-inline" method="post" action="{{ route('cat.clientes.destroy', $c->cliente_id) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar cliente?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Sin resultados</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


