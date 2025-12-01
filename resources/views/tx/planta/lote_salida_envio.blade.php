@extends('layouts.app')

@section('page_title', 'Lote de salida y envío')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Lote de salida / Envío</h1>
            <p class="text-secondary mb-0 small">Empaque del lote y opción de crear un envío logístico</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-7">
            @include('components.alerts')

            {{-- Tarjeta para registrar lote de salida y opcionalmente un envío --}}
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Registrar lote de salida / envío</h3>
                </div>
                <form method="post" action="{{ route('tx.planta.lote-salida-envio.store') }}">
                    @csrf
                    <div class="card-body">
                        {{-- Sección: datos del lote de salida --}}
                        <h5 class="mb-3">Lote de salida</h5>
                        <div class="form-group">
                            <label for="codigo_lote_salida">Código lote salida</label>
                            <input
                                id="codigo_lote_salida"
                                name="codigo_lote_salida"
                                class="form-control"
                                maxlength="50"
                                required
                                value="{{ old('codigo_lote_salida', 'LS-'.date('Ymd-His')) }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="lote_planta_id">Lote planta origen *</label>
                            <select id="lote_planta_id" name="lote_planta_id" class="form-control" required>
                                <option value="">Seleccione un lote de planta...</option>
                                @foreach($lotesPlanta as $lp)
                                    <option
                                        value="{{ $lp->lote_planta_id }}"
                                        {{ (int) old('lote_planta_id') === $lp->lote_planta_id ? 'selected' : '' }}
                                    >
                                        {{ $lp->codigo_lote_planta }} - 
                                        {{ $lp->planta_nombre }} 
                                        ({{ $lp->total_lotes_campo }} lotes campo, {{ number_format($lp->peso_entrada_total, 2) }} t) - 
                                        {{ \Carbon\Carbon::parse($lp->fecha_inicio)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Seleccione el lote de planta del cual saldrá el producto empacado</small>
                        </div>
                        <div class="form-group">
                            <label for="sku">SKU</label>
                            <input
                                id="sku"
                                name="sku"
                                class="form-control"
                                maxlength="120"
                                required
                                value="{{ old('sku', 'Papa lavada 25kg') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="peso_t">Peso (t)</label>
                            <input
                                type="number"
                                step="0.001"
                                min="0"
                                id="peso_t"
                                name="peso_t"
                                class="form-control"
                                required
                                value="{{ old('peso_t') }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="fecha_empaque">Fecha empaque</label>
                            <input
                                type="datetime-local"
                                id="fecha_empaque"
                                name="fecha_empaque"
                                class="form-control"
                                required
                                value="{{ old('fecha_empaque') }}"
                            >
                        </div>

                        <hr>

                        {{-- Sección: datos opcionales del envío --}}
                        <h5 class="mb-3">Crear envío (opcional)</h5>
                        <div class="form-group form-check">
                            <input
                                type="checkbox"
                                id="crear_envio"
                                name="crear_envio"
                                class="form-check-input"
                                value="1"
                                {{ old('crear_envio') ? 'checked' : '' }}
                            >
                            <label for="crear_envio" class="form-check-label">
                                Crear también un envío logístico
                            </label>
                        </div>
                        <div id="seccion-envio">
                            <div class="form-group">
                                <label for="codigo_envio">Código envío</label>
                                <input
                                    id="codigo_envio"
                                    name="codigo_envio"
                                    class="form-control"
                                    maxlength="40"
                                    value="{{ old('codigo_envio', 'ENV-'.date('Ymd-His')) }}"
                                >
                            </div>
                            <div class="form-group">
                                <label for="ruta_id">Ruta</label>
                                <select id="ruta_id" name="ruta_id" class="form-control">
                                    <option value="">(Opcional)</option>
                                    @foreach($rutas as $r)
                                        <option
                                            value="{{ $r->ruta_id }}"
                                            {{ (int) old('ruta_id') === $r->ruta_id ? 'selected' : '' }}
                                        >
                                            {{ $r->codigo_ruta }} - {{ $r->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="transportista_id">Transportista</label>
                                <select id="transportista_id" name="transportista_id" class="form-control">
                                    <option value="">(Opcional)</option>
                                    @foreach($transportistas as $t)
                                        <option
                                            value="{{ $t->transportista_id }}"
                                            {{ (int) old('transportista_id') === $t->transportista_id ? 'selected' : '' }}
                                        >
                                            {{ $t->codigo_transp }} - {{ $t->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha_salida">Fecha salida (si se crea envío)</label>
                                <input
                                    type="datetime-local"
                                    id="fecha_salida"
                                    name="fecha_salida"
                                    class="form-control"
                                    value="{{ old('fecha_salida') }}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('panel.planta') }}" class="btn btn-outline-secondary">Volver a panel planta</a>
                        <button type="submit" class="btn btn-dark">Ejecutar transacción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script simple para habilitar/deshabilitar sección de envío --}}
    <script>
        (function () {
            const chk = document.getElementById('crear_envio');
            const seccionEnvio = document.getElementById('seccion-envio');
            if (!chk || !seccionEnvio) {
                return;
            }
            const refrescar = () => {
                const activo = chk.checked;
                seccionEnvio.querySelectorAll('input, select').forEach((el) => {
                    el.disabled = !activo;
                });
            };
            chk.addEventListener('change', refrescar);
            refrescar();
        })();
    </script>
@endsection


