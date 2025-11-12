@extends('layouts.app')

@section('page_title', 'Editar planta')

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Editar planta</h3>
                </div>
                <form method="post" action="{{ route('cat.plantas.update', $planta->planta_id) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_planta">Código</label>
                            <input id="codigo_planta" name="codigo_planta" class="form-control" maxlength="40" required value="{{ old('codigo_planta', $planta->codigo_planta) }}">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input id="nombre" name="nombre" class="form-control" maxlength="140" required value="{{ old('nombre', $planta->nombre) }}">
                        </div>
                        <div class="form-group">
                            <label for="municipio_id">Municipio</label>
                            <select id="municipio_id" name="municipio_id" class="form-control" required>
                                @foreach($municipios as $m)
                                    <option value="{{ $m->municipio_id }}" {{ old('municipio_id', $planta->municipio_id) == $m->municipio_id ? 'selected' : '' }}>
                                        {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input id="direccion" name="direccion" class="form-control" maxlength="200" value="{{ old('direccion', $planta->direccion) }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="lat">Lat</label>
                                <input id="lat" name="lat" class="form-control" type="number" step="0.000001" value="{{ old('lat', $planta->lat) }}">
                            </div>
                            <div class="form-group col">
                                <label for="lon">Lon</label>
                                <input id="lon" name="lon" class="form-control" type="number" step="0.000001" value="{{ old('lon', $planta->lon) }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('cat.plantas.index') }}" class="btn btn-outline-secondary">Volver</a>
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


