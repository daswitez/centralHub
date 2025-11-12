@extends('layouts.app')

@section('page_title', 'Editar departamento')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar departamento</h3>
                </div>
                <form method="post" action="{{ route('cat.departamentos.update', $departamento->departamento_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" maxlength="80" required
                                   value="{{ old('nombre', $departamento->nombre) }}" aria-label="Nombre de departamento">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.departamentos.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


