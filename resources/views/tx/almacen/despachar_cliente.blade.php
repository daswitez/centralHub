@extends('layouts.app')

@section('page_title', 'Almacén — Despachar a cliente')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-7">
            @include('components.alerts')

            {{-- Tarjeta para ejecutar almacen.sp_despachar_a_cliente --}}
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Despachar desde almacén a cliente</h3>
                </div>
                <form method="post" action="{{ route('tx.almacen.despachar-al-cliente.store') }}">
                    @csrf
                    <div class="card-body">
                        <h5 class="mb-3">Datos generales del envío</h5>
                        <div class="form-group">
                            <label for="codigo_envio">Código envío</label>
                            <input
                                id="codigo_envio"
                                name="codigo_envio"
                                class="form-control"
                                maxlength="40"
                                required
                                value="{{ old('codigo_envio', 'ENV-'.date('Ymd-His')) }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="almacen_origen_id">Almacén origen</label>
                            <select id="almacen_origen_id" name="almacen_origen_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($almacenes as $a)
                                    <option
                                        value="{{ $a->almacen_id }}"
                                        {{ (int) old('almacen_origen_id') === $a->almacen_id ? 'selected' : '' }}
                                    >
                                        {{ $a->codigo_almacen }} - {{ $a->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="cliente_id">Cliente destino</label>
                            <select id="cliente_id" name="cliente_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($clientes as $c)
                                    <option
                                        value="{{ $c->cliente_id }}"
                                        {{ (int) old('cliente_id') === $c->cliente_id ? 'selected' : '' }}
                                    >
                                        {{ $c->codigo_cliente }} - {{ $c->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="transportista_id">Transportista</label>
                            <select id="transportista_id" name="transportista_id" class="form-control" required>
                                <option value="">Seleccione...</option>
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
                            <label for="fecha_salida">Fecha salida</label>
                            <input
                                type="datetime-local"
                                id="fecha_salida"
                                name="fecha_salida"
                                class="form-control"
                                required
                                value="{{ old('fecha_salida') }}"
                            >
                        </div>

                        <hr>

                        {{-- Detalle de lotes enviados al cliente --}}
                        <h5 class="mb-3">Detalle de lotes enviados al cliente</h5>
                        <p class="text-muted">
                            Cada fila representa la cantidad de un lote de salida que se despacha al cliente.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" id="tabla-detalle-cliente">
                                <thead class="thead-light">
                                <tr>
                                    <th style="width: 55%;">Lote salida</th>
                                    <th style="width: 25%;">Cantidad (t)</th>
                                    <th style="width: 20%;">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $oldDetalle = old('detalle', [
                                        ['codigo_lote_salida' => null, 'cantidad_t' => null],
                                    ]);
                                @endphp
                                @foreach($oldDetalle as $idx => $d)
                                    <tr>
                                        <td>
                                            <select
                                                name="detalle[{{ $idx }}][codigo_lote_salida]"
                                                class="form-control"
                                                required
                                            >
                                                <option value="">Seleccione...</option>
                                                @foreach($lotesSalida as $ls)
                                                    <option
                                                        value="{{ $ls->codigo_lote_salida }}"
                                                        {{ ($d['codigo_lote_salida'] ?? '') === $ls->codigo_lote_salida ? 'selected' : '' }}
                                                    >
                                                        {{ $ls->codigo_lote_salida }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                name="detalle[{{ $idx }}][cantidad_t]"
                                                class="form-control"
                                                required
                                                value="{{ $d['cantidad_t'] ?? '' }}"
                                            >
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remover-fila">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-outline-dark btn-sm" id="btn-agregar-fila-cliente">
                                <i class="fas fa-plus mr-1"></i> Agregar fila
                            </button>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('panel.logistica') }}" class="btn btn-outline-secondary">Volver a panel logística</a>
                        <button type="submit" class="btn btn-dark">Ejecutar transacción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para gestionar filas dinámicas de detalle hacia cliente --}}
    <script>
        (function () {
            const tabla = document.getElementById('tabla-detalle-cliente');
            const btnAgregar = document.getElementById('btn-agregar-fila-cliente');
            if (!tabla || !btnAgregar) {
                return;
            }

            const handleAgregar = () => {
                const tbody = tabla.querySelector('tbody');
                const filas = tbody.querySelectorAll('tr');
                const nextIndex = filas.length;
                const plantilla = filas[filas.length - 1];
                if (!plantilla) {
                    return;
                }
                const nueva = plantilla.cloneNode(true);
                nueva.querySelectorAll('select, input').forEach((el) => {
                    if (el.name) {
                        el.name = el.name.replace(/\[\d+]/, '[' + nextIndex + ']');
                    }
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    } else {
                        el.value = '';
                    }
                });
                tbody.appendChild(nueva);
            };

            const handleClick = (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) {
                    return;
                }
                if (target.classList.contains('btn-remover-fila') || target.closest('.btn-remover-fila')) {
                    const btn = target.closest('.btn-remover-fila');
                    const fila = btn && btn.closest('tr');
                    const tbody = fila && fila.parentElement;
                    if (!fila || !tbody) {
                        return;
                    }
                    if (tbody.querySelectorAll('tr').length <= 1) {
                        return;
                    }
                    tbody.removeChild(fila);
                }
            };

            btnAgregar.addEventListener('click', handleAgregar);
            tabla.addEventListener('click', handleClick);
        })();
    </script>
@endsection


