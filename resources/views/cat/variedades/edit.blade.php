@extends('layouts.app')

@section('page_title', 'Editar variedad')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar variedad de papa</h3>
                </div>
                <form method="post" action="{{ route('cat.variedades.update', $variedad->variedad_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_variedad">Código</label>
                            <input type="text" id="codigo_variedad" name="codigo_variedad" class="form-control" maxlength="40" required
                                   value="{{ old('codigo_variedad', $variedad->codigo_variedad) }}">
                        </div>
                        <div class="form-group">
                            <label for="nombre_comercial">Nombre comercial</label>
                            <input type="text" id="nombre_comercial" name="nombre_comercial" class="form-control" maxlength="120" required
                                   value="{{ old('nombre_comercial', $variedad->nombre_comercial) }}">
                        </div>
                        <div class="form-group">
                            <label for="aptitud">Aptitud</label>
                            <input type="text" id="aptitud" name="aptitud" class="form-control" maxlength="80"
                                   value="{{ old('aptitud', $variedad->aptitud) }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="ciclo_dias_min">Ciclo mín (días)</label>
                                <input type="number" id="ciclo_dias_min" name="ciclo_dias_min" class="form-control"
                                       value="{{ old('ciclo_dias_min', $variedad->ciclo_dias_min) }}">
                            </div>
                            <div class="form-group col">
                                <label for="ciclo_dias_max">Ciclo máx (días)</label>
                                <input type="number" id="ciclo_dias_max" name="ciclo_dias_max" class="form-control"
                                       value="{{ old('ciclo_dias_max', $variedad->ciclo_dias_max) }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.variedades.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


