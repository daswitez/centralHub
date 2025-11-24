@extends('layouts.app')

@section('page_title', 'Almacén — Recepcionar envío')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Recepcionar envío</h1>
            <p class="text-secondary mb-0 small">Confirma la llegada del envío y actualiza inventario</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            @include('components.alerts')

            {{-- Tarjeta para ejecutar almacen.sp_recepcionar_envio --}}
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Recepcionar envío en almacén</h3>
                </div>
                <form method="post" action="{{ route('tx.almacen.recepcionar-envio.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="codigo_envio">Código envío</label>
                            <input
                                id="codigo_envio"
                                name="codigo_envio"
                                class="form-control"
                                maxlength="40"
                                required
                                value="{{ old('codigo_envio') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="almacen_id">Almacén receptor</label>
                            <select id="almacen_id" name="almacen_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($almacenes as $a)
                                    <option
                                        value="{{ $a->almacen_id }}"
                                        {{ (int) old('almacen_id') === $a->almacen_id ? 'selected' : '' }}
                                    >
                                        {{ $a->codigo_almacen }} - {{ $a->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="observacion">Observación (opcional)</label>
                            <textarea
                                id="observacion"
                                name="observacion"
                                rows="3"
                                class="form-control"
                                maxlength="200"
                            >{{ old('observacion') }}</textarea>
                        </div>
                        <p class="text-muted mb-0">
                            Esta operación también actualiza inventario y registra movimientos de entrada.
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('panel.logistica') }}" class="btn btn-outline-secondary">Volver a panel logística</a>
                        <button type="submit" class="btn btn-dark">Ejecutar transacción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


