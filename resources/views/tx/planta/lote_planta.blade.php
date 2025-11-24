@extends('layouts.app')

@section('page_title', 'Registrar lote de planta')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Nuevo lote de planta</h1>
            <p class="text-secondary mb-0 small">Registra un batch de producción y sus entradas de campo</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-7">
            @include('components.alerts')

            {{-- Tarjeta principal para registrar un nuevo lote de planta usando la función planta.sp_registrar_lote_planta --}}
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Registrar lote de planta</h3>
                </div>
                <form method="post" action="{{ route('tx.planta.lote-planta.store') }}">
                    @csrf
                    <div class="card-body">
                        {{-- Datos generales del lote de planta --}}
                        <div class="form-group">
                            <label for="codigo_lote_planta">Código lote planta</label>
                            <input
                                id="codigo_lote_planta"
                                name="codigo_lote_planta"
                                class="form-control"
                                maxlength="50"
                                required
                                value="{{ old('codigo_lote_planta', 'LP-'.date('Ymd-His')) }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="planta_id">Planta</label>
                            <select id="planta_id" name="planta_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($plantas as $p)
                                    <option value="{{ $p->planta_id }}" {{ (int) old('planta_id') === $p->planta_id ? 'selected' : '' }}>
                                        {{ $p->codigo_planta }} - {{ $p->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha inicio proceso</label>
                            <input
                                type="datetime-local"
                                id="fecha_inicio"
                                name="fecha_inicio"
                                class="form-control"
                                required
                                value="{{ old('fecha_inicio') }}"
                            >
                        </div>

                        <hr>

                        {{-- Tabla de entradas de campo (JSONB en la función) --}}
                        <h5 class="mb-1 text-uppercase">Entradas de campo</h5>
                        <p class="text-muted small mb-2">Cada fila representa una entrada desde un lote de campo con su peso en toneladas.</p>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0" id="tabla-entradas">
                                <thead class="thead-light">
                                <tr>
                                    <th style="width: 55%;">Lote de campo</th>
                                    <th style="width: 25%;">Peso entrada (t)</th>
                                    <th style="width: 20%;">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $oldEntradas = old('entradas', [
                                        ['lote_campo_id' => null, 'peso_entrada_t' => null],
                                    ]);
                                @endphp
                                @foreach($oldEntradas as $idx => $entrada)
                                    <tr>
                                        <td>
                                            <select
                                                name="entradas[{{ $idx }}][lote_campo_id]"
                                                class="form-control"
                                                required
                                            >
                                                <option value="">Seleccione...</option>
                                                @foreach($lotesCampo as $lc)
                                                    <option
                                                        value="{{ $lc->lote_campo_id }}"
                                                        {{ (int) ($entrada['lote_campo_id'] ?? 0) === $lc->lote_campo_id ? 'selected' : '' }}
                                                    >
                                                        {{ $lc->codigo_lote_campo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                name="entradas[{{ $idx }}][peso_entrada_t]"
                                                class="form-control"
                                                required
                                                value="{{ $entrada['peso_entrada_t'] ?? '' }}"
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
                            <button type="button" class="btn btn-outline-dark btn-sm" id="btn-agregar-fila">
                                <i class="fas fa-plus mr-1"></i> Agregar fila
                            </button>
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

    {{-- Script pequeño para gestionar filas dinámicas de entradas --}}
    <script>
        (function () {
            const tablaEntradas = document.getElementById('tabla-entradas');
            const btnAgregarFila = document.getElementById('btn-agregar-fila');

            if (!tablaEntradas || !btnAgregarFila) {
                return;
            }

            const handleAgregarFila = () => {
                const tbody = tablaEntradas.querySelector('tbody');
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

            const handleClickTabla = (event) => {
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

            btnAgregarFila.addEventListener('click', handleAgregarFila);
            tablaEntradas.addEventListener('click', handleClickTabla);
        })();
    </script>
@endsection


