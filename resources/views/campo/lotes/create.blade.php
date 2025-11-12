@extends('layouts.app')

@section('page_title', 'Nuevo lote de campo')

@section('content')
    <div class="row">
        <div class="col-12 col-md-8">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Crear lote de campo</h3>
                </div>
                <form method="post" action="{{ route('campo.lotes.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="codigo_lote_campo">Código lote</label>
                                <input id="codigo_lote_campo" name="codigo_lote_campo" class="form-control" maxlength="50" required value="{{ old('codigo_lote_campo') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="productor_id">Productor</label>
                                <select id="productor_id" name="productor_id" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($productores as $p)
                                        <option value="{{ $p->productor_id }}" {{ old('productor_id') == $p->productor_id ? 'selected' : '' }}>
                                            {{ $p->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="variedad_id">Variedad</label>
                                <select id="variedad_id" name="variedad_id" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($variedades as $v)
                                        <option value="{{ $v->variedad_id }}" {{ old('variedad_id') == $v->variedad_id ? 'selected' : '' }}>
                                            {{ $v->nombre_comercial }} ({{ $v->codigo_variedad }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="superficie_ha">Superficie (ha)</label>
                                <input id="superficie_ha" name="superficie_ha" class="form-control" type="number" step="0.01" required value="{{ old('superficie_ha') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="humedad_suelo_pct">Humedad suelo (%)</label>
                                <input id="humedad_suelo_pct" name="humedad_suelo_pct" class="form-control" type="number" step="0.01" value="{{ old('humedad_suelo_pct') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="fecha_siembra">Fecha siembra</label>
                                <input id="fecha_siembra" name="fecha_siembra" class="form-control" type="date" required value="{{ old('fecha_siembra') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_cosecha">Fecha cosecha</label>
                                <input id="fecha_cosecha" name="fecha_cosecha" class="form-control" type="date" value="{{ old('fecha_cosecha') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('campo.lotes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


