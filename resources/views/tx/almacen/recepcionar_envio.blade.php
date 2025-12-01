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
        <div class="col-12 col-lg-10">
            @include('components.alerts')

            {{-- Tarjeta para ejecutar almacen.sp_recepcionar_envio --}}
            <div class="card card-outline card-dark shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Recepcionar envío en almacén</h3>
                </div>
                <form method="post" action="{{ route('tx.almacen.recepcionar-envio.store') }}" id="formRecepcionar">
                    @csrf
                    <div class="card-body">
                        {{-- Paso 1: Seleccionar envío --}}
                        <div class="form-group">
                            <label for="codigo_envio">Seleccionar Envío *</label>
                            <select 
                                id="codigo_envio"
                                name="codigo_envio"
                                class="form-control"
                                required
                            >
                                <option value="">Seleccione un envío...</option>
                                @foreach($envios as $envio)
                                    <option value="{{ $envio->codigo_envio }}" 
                                            data-envio='@json($envio)'>
                                        {{ $envio->codigo_envio }} - 
                                        {{ $envio->ruta_descripcion ?? $envio->codigo_ruta ?? 'Sin ruta' }} 
                                        ({{ number_format($envio->peso_total, 2) }} t) - 
                                        {{ ucfirst(strtolower($envio->estado)) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Seleccione el envío a recepcionar de la lista</small>
                        </div>

                        {{-- Panel de información del envío (se muestra después de buscar) --}}
                        <div id="envioInfo" style="display: none;">
                            <hr>
                            <h5 class="mb-3 text-uppercase">
                                <i class="fas fa-info-circle mr-2"></i>Información del Envío
                            </h5>

                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    {{-- Timeline de trazabilidad --}}
                                    <div id="timelineContainer"></div>

                                    {{-- Detalles del envío --}}
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>📌 Código:</strong> <span id="infoCodigoEnvio"></span></p>
                                            <p class="mb-2"><strong>🚚 Transportista:</strong> <span id="infoTransportista"></span></p>
                                            <p class="mb-2"><strong>📍 Ruta:</strong> <span id="infoRuta"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>📅 Fecha salida:</strong> <span id="infoFechaSalida"></span></p>
                                            <p class="mb-2"><strong>📊 Estado:</strong> <span id="infoEstado"></span></p>
                                            <p class="mb-2"><strong>⚖️ Total:</strong> <span id="infoTotalTon"></span> t</p>
                                        </div>
                                    </div>

                                    {{-- Contenido del envío --}}
                                    <div class="mt-3">
                                        <p class="mb-2"><strong>📦 Contenido:</strong></p>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0" id="tablaContenido">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Lote Salida</th>
                                                        <th>SKU</th>
                                                        <th class="text-right">Cantidad (t)</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Datos de recepción --}}
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="temp_min">🌡️ Temp. mínima registrada (°C)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            id="temp_min"
                                            name="temp_min"
                                            class="form-control"
                                            placeholder="Ej: 2.5"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="temp_max">🌡️ Temp. máxima registrada (°C)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            id="temp_max"
                                            name="temp_max"
                                            class="form-control"
                                            placeholder="Ej: 8.5"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Estado del producto</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado_producto" id="estadoExcelente" value="EXCELENTE">
                                        <label class="form-check-label" for="estadoExcelente">
                                            ✅ Excelente
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado_producto" id="estadoBueno" value="BUENO" checked>
                                        <label class="form-check-label" for="estadoBueno">
                                            👍 Bueno
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado_producto" id="estadoRegular" value="REGULAR">
                                        <label class="form-check-label" for="estadoRegular">
                                            ⚠️ Regular
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="estado_producto" id="estadoMalo" value="MALO">
                                        <label class="form-check-label" for="estadoMalo">
                                            ❌ Mato
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacion">Observaciones</label>
                                <textarea
                                    id="observacion"
                                    name="observacion"
                                    rows="3"
                                    class="form-control"
                                    maxlength="200"
                                    placeholder="Registre cualquier novedad encontrada durante la recepción..."
                                >{{ old('observacion') }}</textarea>
                            </div>

                            {{-- Advertencia de impacto --}}
                            <div class="alert alert-warning">
                                <h6 class="mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Al confirmar:</h6>
                                <ul class="mb-0 pl-3">
                                    <li>El envío se marcará como <strong>ENTREGADO</strong></li>
                                    <li>Se actualizará el inventario del almacén seleccionado</li>
                                    <li>Se registrará la entrada en el histórico</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('panel.logistica') }}" class="btn btn-outline-secondary">Volver a panel logística</a>
                        <button type="submit" class="btn btn-dark" id="btnConfirmar" disabled>
                            <i class="fas fa-check mr-1"></i> Confirmar Recepción
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const codigoEnvioSelect = document.getElementById('codigo_envio');
            const envioInfoPanel = document.getElementById('envioInfo');
            const btnConfirmar = document.getElementById('btnConfirmar');

            if (!codigoEnvioSelect) return;

            // Cuando se selecciona un envío del dropdown
            codigoEnvioSelect.addEventListener('change', async function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (!this.value) {
                    // Si se deselecciona, ocultar panel
                    envioInfoPanel.style.display = 'none';
                    btnConfirmar.disabled = true;
                    return;
                }

                // Obtener datos del envío desde el atributo data
                const envioData = JSON.parse(selectedOption.getAttribute('data-envio'));
                
                // Buscar información completa del envío via API
                try {
                    const response = await fetch(`/api/envios/buscar/${encodeURIComponent(this.value)}`);
                    
                    if (!response.ok) {
                        throw new Error('No se pudo cargar la información del envío');
                    }

                    const data = await response.json();
                    mostrarInfoEnvio(data);
                    btnConfirmar.disabled = false;

                } catch (error) {
                    alert('Error: ' + error.message);
                    this.value = '';
                    envioInfoPanel.style.display = 'none';
                    btnConfirmar.disabled = true;
                }
            });

            function mostrarInfoEnvio(envio) {
                // Mostrar panel
                envioInfoPanel.style.display = 'block';

                // Timeline
                const stages = [
                    {
                        label: 'Salida',
                        icon: 'truck-loading',
                        status: 'completed',
                        date: envio.fecha_salida ? new Date(envio.fecha_salida).toLocaleDateString() : null,
                        details: envio.origen || ''
                    },
                    {
                        label: 'En tránsito',
                        icon: 'truck',
                        status: envio.estado === 'EN_RUTA' ? 'in_progress' : 'completed',
                        date: null
                    },
                    {
                        label: 'Llegada',
                        icon: 'warehouse',
                        status: 'in_progress',
                        date: 'Hoy'
                    }
                ];

                const timelineHtml = stages.map((stage, index) => `
                    <div class="timeline-stage ${stage.status}">
                        <div class="timeline-icon-wrapper">
                            <div class="timeline-icon">
                                <i class="fas fa-${stage.icon}"></i>
                            </div>
                            ${index < stages.length - 1 ? '<div class="timeline-connector"></div>' : ''}
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-label">${stage.label}</div>
                            ${stage.date ? `<div class="timeline-date"><i class="far fa-clock"></i> ${stage.date}</div>` : ''}
                            ${stage.details ? `<div class="timeline-details">${stage.details}</div>` : ''}
                        </div>
                    </div>
                `).join('');

                document.getElementById('timelineContainer').innerHTML = '<div class="traceability-timeline">' + timelineHtml + '</div>';

                // Información básica
                document.getElementById('infoCodigoEnvio').textContent = envio.codigo_envio;
                document.getElementById('infoTransportista').textContent = envio.transportista || '—';
                document.getElementById('infoRuta').textContent = envio.ruta || '—';
                document.getElementById('infoFechaSalida').textContent = envio.fecha_salida ? new Date(envio.fecha_salida).toLocaleString() : '—';
                
                const estadoBadge = `<span class="badge badge-${envio.estado === 'EN_RUTA' ? 'warning' : 'secondary'}">${envio.estado}</span>`;
                document.getElementById('infoEstado').innerHTML = estadoBadge;
                document.getElementById('infoTotalTon').textContent = parseFloat(envio.total_ton || 0).toFixed(2);

                // Tabla de contenido
                const tbody = document.querySelector('#tablaContenido tbody');
                tbody.innerHTML = '';
                
                if (envio.detalles && envio.detalles.length > 0) {
                    envio.detalles.forEach(detalle => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${detalle.codigo_lote_salida || '—'}</td>
                            <td>${detalle.sku || '—'}</td>
                            <td class="text-right">${parseFloat(detalle.cantidad_t || 0).toFixed(3)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Sin detalles</td></tr>';
                }
            }
        })();
    </script>

    <style>
        /* Estilos del timeline (copiados del componente) */
        .traceability-timeline {
            padding: 1rem 0;
        }
        .timeline-stage {
            display: flex;
            gap: 1rem;
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-stage:last-child {
            padding-bottom: 0;
        }
        .timeline-icon-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            z-index: 2;
            background: #fff;
            border: 3px solid #6c757d;
            color: #6c757d;
        }
        .timeline-stage.completed .timeline-icon {
            border-color: #28a745;
            background: #28a745;
            color: #fff;
        }
        .timeline-stage.in_progress .timeline-icon {
            border-color: #ffc107;
            background: #ffc107;
            color: #000;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .timeline-connector {
            width: 3px;
            flex-grow: 1;
            margin-top: 0.25rem;
            background: #dee2e6;
        }
        .timeline-stage.completed .timeline-connector {
            background: #28a745;
        }
        .timeline-stage.in_progress .timeline-connector {
            background: linear-gradient(to bottom, #ffc107 0%, #dee2e6 100%);
        }
        .timeline-content {
            flex-grow: 1;
            padding-top: 0.5rem;
        }
        .timeline-label {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .timeline-date {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        .timeline-details {
            font-size: 0.875rem;
            color: #495057;
            font-family: monospace;
        }
    </style>
@endsection
