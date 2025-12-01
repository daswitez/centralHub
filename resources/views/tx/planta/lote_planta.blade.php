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
        <div class="col-12 col-lg-10">
            @include('components.alerts')

            {{-- Tarjeta principal para registrar un nuevo lote de planta usando la función planta.sp_registrar_lote_planta --}}
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Registrar lote de planta</h3>
                </div>
                <form method="post" action="{{ route('tx.planta.lote-planta.store') }}" id="formLotePlanta">
                    @csrf
                    <div class="card-body">
                        {{-- Datos generales del lote de planta --}}
                        <h5 class="mb-3 text-uppercase">
                            <i class="fas fa-info-circle mr-2"></i>Información General
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha inicio proceso</label>
                                    <input
                                        type="datetime-local"
                                        id="fecha_inicio"
                                        name="fecha_inicio"
                                        class="form-control"
                                        required
                                        value="{{ old('fecha_inicio', now()->format('Y-m-d\TH:i')) }}"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="planta_id">Planta</label>
                            <select id="planta_id" name="planta_id" class="form-control" required>
                                <option value="">Seleccione...</option>
                                @foreach($plantas as $p)
                                    <option value="{{ $p->planta_id }}" 
                                            data-rendimiento="{{ $rendimientos[$p->planta_id]['promedio'] ?? 0 }}"
                                            data-num-lotes="{{ $rendimientos[$p->planta_id]['num_lotes'] ?? 0 }}"
                                            {{ (int) old('planta_id') === $p->planta_id ? 'selected' : '' }}>
                                        {{ $p->codigo_planta }} - {{ $p->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted" id="rendimientoInfo"></small>
                        </div>

                        <hr>

                        {{-- Tabla de entradas de campo (JSONB en la función) --}}
                        <h5 class="mb-2 text-uppercase">
                            <i class="fas fa-seedling mr-2"></i>Entradas de Campo
                        </h5>
                        <p class="text-muted small mb-3">Seleccione los lotes de campo que se procesarán. Solo se muestran lotes con peso disponible.</p>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover mb-0" id="tabla-entradas">
                                <thead class="thead-light">
                                <tr>
                                    <th style="width: 35%;">Lote de Campo</th>
                                    <th style="width: 20%;">Variedad</th>
                                    <th style="width: 20%;">Productor</th>
                                    <th class="text-right" style="width: 15%;">Peso entrada (t)</th>
                                    <th style="width: 10%;">&nbsp;</th>
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
                                                class="form-control form-control-sm lote-select"
                                                required
                                                data-index="{{ $idx }}"
                                            >
                                                <option value="">Seleccione...</option>
                                                @foreach($lotesCampo as $lc)
                                                    <option
                                                        value="{{ $lc->lote_campo_id }}"
                                                        data-variedad="{{ $lc->variedad_nombre }}"
                                                        data-productor="{{ $lc->productor_nombre }}"
                                                        {{ (int) ($entrada['lote_campo_id'] ?? 0) === (int)$lc->lote_campo_id ? 'selected' : '' }}
                                                    >
                                                        {{ $lc->codigo_lote_campo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="variedad-cell">—</td>
                                        <td class="productor-cell">—</td>
                                        <td>
                                            <input
                                                type="number"
                                                step="0.001"
                                                min="0"
                                                name="entradas[{{ $idx }}][peso_entrada_t]"
                                                class="form-control form-control-sm peso-input"
                                                required
                                                value="{{ $entrada['peso_entrada_t'] ?? '' }}"
                                                data-index="{{ $idx }}"
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
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="4" class="text-right"><strong>Total peso entrada:</strong></td>
                                        <td class="text-right"><strong id="totalPeso">0.000</strong> t</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-outline-dark btn-sm" id="btn-agregar-fila">
                                <i class="fas fa-plus mr-1"></i> Agregar lote
                            </button>
                        </div>

                        {{-- Resumen y estimaciones --}}
                        <div class="card bg-light mt-3" id="resumenCard" style="display: none;">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-calculator mr-2"></i>Resumen y Estimaciones</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Peso total entrada:</strong></p>
                                        <p class="h5 text-primary" id="resumenPeso">0.000 t</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Rendimiento estimado*:</strong></p>
                                        <p class="h5 text-info" id="resumenRendimiento">—</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Peso salida esperado:</strong></p>
                                        <p class="h5 text-success" id="resumenSalida">—</p>
                                    </div>
                                </div>
                                <small class="text-muted">*Basado en histórico de la planta seleccionada</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('panel.planta') }}" class="btn btn-outline-secondary">Volver a panel planta</a>
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-check mr-1"></i> Procesar Lote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script para gestionar filas dinámicas y cálculos --}}
    <script>
        (function () {
            const tablaEntradas = document.getElementById('tabla-entradas');
            const btnAgregarFila = document.getElementById('btn-agregar-fila');
            const plantaSelect = document.getElementById('planta_id');
            const rendimientoInfo = document.getElementById('rendimientoInfo');

            if (!tablaEntradas || !btnAgregarFila) {
                return;
            }

            // Actualizar info de rendimiento al cambiar planta
            if (plantaSelect) {
                plantaSelect.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    const rendimiento = option.dataset.rendimiento;
                    const numLotes = option.dataset.numLotes;
                    
                    if (rendimiento && rendimiento > 0) {
                        rendimientoInfo.textContent = `Rendimiento histórico: ${rendimiento}% (basado en ${numLotes} lotes procesados)`;
                        rendimientoInfo.classList.add('text-info');
                    } else {
                        rendimientoInfo.textContent = 'Sin histórico de rendimiento para esta planta';
                        rendimientoInfo.classList.remove('text-info');
                    }
                    
                    calcularResumen();
                });
            }

            // Actualizar celdas cuando se selecciona un lote
            tablaEntradas.addEventListener('change', function(e) {
                if (e.target.classList.contains('lote-select')) {
                    const select = e.target;
                    const row = select.closest('tr');
                    const option = select.options[select.selectedIndex];
                    
                    if (option.value) {
                        row.querySelector('.variedad-cell').textContent = option.dataset.variedad || '—';
                        row.querySelector('.productor-cell').textContent = option.dataset.productor || '—';
                    } else {
                        row.querySelector('.variedad-cell').textContent = '—';
                        row.querySelector('.productor-cell').textContent = '—';
                    }
                    
                    calcularTotales();
                }
                
                if (e.target.classList.contains('peso-input')) {
                    calcularTotales();
                }
            });

            function calcularTotales() {
                let total = 0;
                document.querySelectorAll('.peso-input').forEach(input => {
                    const val = parseFloat(input.value) || 0;
                    total += val;
                });
                
                document.getElementById('totalPeso').textContent = total.toFixed(3);
                calcularResumen();
            }

            function calcularResumen() {
                const total = parseFloat(document.getElementById('totalPeso').textContent) || 0;
                const plantaOption = plantaSelect.options[plantaSelect.selectedIndex];
                const rendimiento = parseFloat(plantaOption?.dataset?.rendimiento) || 0;
                
                document.getElementById('resumenPeso').textContent = total.toFixed(3) + ' t';
                
                if (total > 0) {
                    document.getElementById('resumenCard').style.display = 'block';
                    
                    if (rendimiento > 0) {
                        document.getElementById('resumenRendimiento').textContent = rendimiento + '%';
                        const salidaEstimada = total * (rendimiento / 100);
                        document.getElementById('resumenSalida').textContent = salidaEstimada.toFixed(3) + ' t';
                    } else {
                        document.getElementById('resumenRendimiento').textContent = '—';
                        document.getElementById('resumenSalida').textContent = '—';
                    }
                } else {
                    document.getElementById('resumenCard').style.display = 'none';
                }
            }

            // Agregar fila
            btnAgregarFila.addEventListener('click', function() {
                const tbody = tablaEntradas.querySelector('tbody');
                const filas = tbody.querySelectorAll('tr');
                const nextIndex = filas.length;
                const plantilla = filas[filas.length - 1];
                
                if (!plantilla) return;
                
                const nueva = plantilla.cloneNode(true);
                
                nueva.querySelectorAll('select, input').forEach((el) => {
                    if (el.name) {
                        el.name = el.name.replace(/\[\d+]/, '[' + nextIndex + ']');
                        el.dataset.index = nextIndex;
                    }
                    if (el.tagName === 'SELECT') {
                        el.selectedIndex = 0;
                    } else {
                        el.value = '';
                    }
                });
                
                nueva.querySelector('.variedad-cell').textContent = '—';
                nueva.querySelector('.productor-cell').textContent = '—';
                
                tbody.appendChild(nueva);
                calcularTotales();
            });

            // Remover fila
            tablaEntradas.addEventListener('click', function(e) {
                const target = e.target;
                if (target.classList.contains('btn-remover-fila') || target.closest('.btn-remover-fila')) {
                    const btn = target.closest('.btn-remover-fila');
                    const fila = btn?.closest('tr');
                    const tbody = fila?.parentElement;
                    
                    if (!fila || !tbody || tbody.querySelectorAll('tr').length <= 1) {
                        return;
                    }
                    
                    tbody.removeChild(fila);
                    calcularTotales();
                }
            });

            // Inicializar
            document.querySelectorAll('.lote-select').forEach(select => {
                if (select.value) {
                    const event = new Event('change', { bubbles: true });
                    select.dispatchEvent(event);
                }
            });
        })();
    </script>
@endsection
