@extends('layouts.app')

@section('page_title', 'Nueva lectura de sensor')

@section('content')
    <div class="row">
        <div class="col-12 col-md-7">
            @include('components.alerts')
            <div class="card card-outline card-dark">
                <div class="card-header"><h3 class="card-title">Registrar lectura</h3></div>
                <form method="post" action="{{ route('campo.lecturas.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="lote_campo_id">Lote</label>
                                <select id="lote_campo_id" name="lote_campo_id" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach($lotes as $l)
                                        <option value="{{ $l->lote_campo_id }}" {{ old('lote_campo_id') == $l->lote_campo_id ? 'selected' : '' }}>
                                            #{{ $l->lote_campo_id }} — {{ $l->codigo_lote_campo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_hora">Fecha/Hora</label>
                                <input id="fecha_hora" name="fecha_hora" type="datetime-local" class="form-control" required value="{{ old('fecha_hora') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tipo">Tipo</label>
                                <input id="tipo" name="tipo" class="form-control" maxlength="50" required value="{{ old('tipo') }}" placeholder="humedad|temperatura|ph|...">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="valor_num">Valor numérico</label>
                                <input id="valor_num" name="valor_num" type="number" step="0.000001" class="form-control" value="{{ old('valor_num') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="valor_texto">Valor texto</label>
                                <input id="valor_texto" name="valor_texto" class="form-control" maxlength="200" value="{{ old('valor_texto') }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('campo.lecturas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


