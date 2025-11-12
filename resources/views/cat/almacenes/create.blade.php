@extends('layouts.app')

@section('page_title', 'Nuevo almacén')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Crear almacén</h3>
                </div>
                <form method="post" action="{{ route('cat.almacenes.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_almacen">Código</label>
                            <input id="codigo_almacen" name="codigo_almacen" class="form-control" maxlength="40" required value="{{ old('codigo_almacen') }}">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" class="form-control" maxlength="140" required value="{{ old('nombre') }}">
                        </div>
                        <div class="form-group">
                            <label for="municipio_id">Municipio</label>
                            <select id="municipio_id" name="municipio_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($municipios as $m)
                                    <option value="{{ $m->municipio_id }}" {{ old('municipio_id') == $m->municipio_id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" class="form-control" maxlength="200" value="{{ old('direccion') }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="lat">Lat</label>
                                <input id="lat" name="lat" class="form-control" type="number" step="0.000001" value="{{ old('lat') }}">
                            </div>
                            <div class="form-group col">
                                <label for="lon">Lon</label>
                                <input id="lon" name="lon" class="form-control" type="number" step="0.000001" value="{{ old('lon') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.almacenes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


