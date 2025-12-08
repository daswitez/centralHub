@extends('layouts.app')

@section('page_title', 'Trazabilidad')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-route text-primary"></i> Trazabilidad de Productos</h1>
            <p class="text-muted mb-0">Seguimiento completo desde el campo hasta el cliente</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Panel de Búsqueda --}}
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header bg-gradient-primary text-white">
            <h3 class="card-title"><i class="fas fa-search"></i> Buscar Trazabilidad</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="font-weight-bold">¿Qué deseas rastrear?</label>
                    <select id="tipoBusqueda" class="form-control form-control-lg" onchange="actualizarDropdown()">
                        <option value="campo">🌱 Lote de Campo (Cosecha)</option>
                        <option value="planta">🏭 Lote de Planta (Procesamiento)</option>
                        <option value="salida">📦 Lote de Salida (Empaque)</option>
                        <option value="orden_envio">🚛 Orden de Envío (Planta → Almacén)</option>
                        <option value="envio">📍 Envío (Transporte)</option>
                        <option value="pedido">📄 Pedido (Cliente)</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="font-weight-bold">Seleccione el código</label>
                    
                    {{-- Dropdown para Lote Campo --}}
                    <select id="dropdown_campo" class="form-control form-control-lg dropdown-lote">
                        <option value="">-- Seleccione lote de campo --</option>
                        @foreach($lotesCampo as $lc)
                            <option value="{{ $lc->codigo_lote_campo }}">
                                {{ $lc->codigo_lote_campo }} ({{ \Carbon\Carbon::parse($lc->fecha_cosecha)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Dropdown para Lote Planta --}}
                    <select id="dropdown_planta" class="form-control form-control-lg dropdown-lote" style="display: none;">
                        <option value="">-- Seleccione lote de planta --</option>
                        @foreach($lotesPlanta as $lp)
                            <option value="{{ $lp->codigo_lote_planta }}">
                                {{ $lp->codigo_lote_planta }} ({{ \Carbon\Carbon::parse($lp->fecha_inicio)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Dropdown para Lote Salida --}}
                    <select id="dropdown_salida" class="form-control form-control-lg dropdown-lote" style="display: none;">
                        <option value="">-- Seleccione lote de salida --</option>
                        @foreach($lotesSalida as $ls)
                            <option value="{{ $ls->codigo_lote_salida }}">
                                {{ $ls->codigo_lote_salida }} ({{ \Carbon\Carbon::parse($ls->fecha_empaque)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Dropdown para Orden Envío --}}
                    <select id="dropdown_orden_envio" class="form-control form-control-lg dropdown-lote" style="display: none;">
                        <option value="">-- Seleccione orden de envío --</option>
                        @foreach($ordenesEnvio ?? [] as $oe)
                            <option value="{{ $oe->codigo_orden }}">
                                {{ $oe->codigo_orden }} ({{ \Carbon\Carbon::parse($oe->fecha_creacion)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Dropdown para Envío --}}
                    <select id="dropdown_envio" class="form-control form-control-lg dropdown-lote" style="display: none;">
                        <option value="">-- Seleccione envío --</option>
                        @foreach($envios as $env)
                            <option value="{{ $env->codigo_envio }}">
                                {{ $env->codigo_envio }} ({{ \Carbon\Carbon::parse($env->fecha_salida)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Dropdown para Pedido --}}
                    <select id="dropdown_pedido" class="form-control form-control-lg dropdown-lote" style="display: none;">
                        <option value="">-- Seleccione pedido --</option>
                        @foreach($pedidos as $ped)
                            <option value="{{ $ped->codigo_pedido }}">
                                {{ $ped->codigo_pedido }} ({{ \Carbon\Carbon::parse($ped->fecha_pedido)->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="buscarTrazabilidad()">
                        <i class="fas fa-search"></i> Rastrear Producto
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Spinner --}}
    <div id="loadingSpinner" style="display: none;" class="text-center py-5">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3 text-muted">Buscando información de trazabilidad...</p>
    </div>

    {{-- Error Alert --}}
    <div id="errorAlert" class="alert alert-danger" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i> 
        <strong>Error:</strong> <span id="errorMessage"></span>
    </div>

    {{-- Resultados de Trazabilidad --}}
    <div id="resultadosTrazabilidad" style="display: none;">
        
        {{-- Timeline Visual --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-stream text-primary"></i> Cadena de Trazabilidad</h4>
            </div>
            <div class="card-body">
                <div id="timelineContainer" class="timeline-horizontal"></div>
            </div>
        </div>

        {{-- Detalles por Etapa --}}
        <div class="row" id="detallesContainer"></div>
    </div>

<style>

/* Timeline Horizontal Moderna */
.timeline-horizontal {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px 0;
    position: relative;
    overflow-x: auto;
}

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 140px;
    position: relative;
    z-index: 1;
}

.timeline-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 50%;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #28a745, #17a2b8);
    z-index: -1;
}

.timeline-step.pending:not(:last-child)::after {
    background: #dee2e6;
}

.step-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    transition: transform 0.3s ease;
}

.step-icon:hover {
    transform: scale(1.1);
}

.timeline-step.pending .step-icon {
    background: linear-gradient(135deg, #6c757d, #adb5bd);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.2);
}

.timeline-step.en_ruta .step-icon,
.timeline-step.conductor_asignado .step-icon {
    background: linear-gradient(135deg, #007bff, #17a2b8);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.step-info {
    text-align: center;
    margin-top: 15px;
}

.step-name {
    font-weight: bold;
    color: #495057;
    font-size: 0.95rem;
}

.step-code {
    font-family: 'Courier New', monospace;
    background: #e9ecef;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    color: #495057;
    margin-top: 5px;
    display: inline-block;
}

.step-date {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
}

/* Tarjetas de Detalle */
.detail-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
}

.detail-card .card-header {
    border-bottom: none;
    padding: 15px 20px;
}

.detail-card .card-header h5 {
    margin: 0;
    font-size: 1rem;
}

.detail-card .card-body {
    padding: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
    font-size: 0.85rem;
}

.detail-value {
    font-weight: 600;
    color: #212529;
    text-align: right;
}

/* Colores por Tipo */
.bg-campo { background: linear-gradient(135deg, #28a745, #20c997); }
.bg-planta { background: linear-gradient(135deg, #6f42c1, #e83e8c); }
.bg-salida { background: linear-gradient(135deg, #fd7e14, #ffc107); }
.bg-orden_envio { background: linear-gradient(135deg, #17a2b8, #007bff); }
.bg-envio { background: linear-gradient(135deg, #007bff, #6610f2); }
.bg-almacen { background: linear-gradient(135deg, #20c997, #198754); }
.bg-pedido { background: linear-gradient(135deg, #e83e8c, #dc3545); }

/* Form Control Grande */
.form-control-lg {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: border-color 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.15);
}
</style>

<script>
// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ Página de trazabilidad cargada');
    actualizarDropdown();
});

function actualizarDropdown() {
    const tipo = document.getElementById('tipoBusqueda').value;
    
    // Ocultar todos los dropdowns
    document.querySelectorAll('.dropdown-lote').forEach(dropdown => {
        dropdown.style.display = 'none';
        dropdown.value = '';
    });
    
    // Mostrar el dropdown correspondiente
    const dropdownActivo = document.getElementById(`dropdown_${tipo}`);
    if (dropdownActivo) {
        dropdownActivo.style.display = 'block';
    }
}

async function buscarTrazabilidad() {
    const tipo = document.getElementById('tipoBusqueda').value;
    const dropdownActivo = document.getElementById(`dropdown_${tipo}`);
    const codigo = dropdownActivo ? dropdownActivo.value : '';

    if (!codigo) {
        alert('Por favor seleccione un elemento para rastrear');
        return;
    }

    // Mostrar loading
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultadosTrazabilidad').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';

    try {
        const response = await fetch(`/api/trazabilidad/${tipo}/${encodeURIComponent(codigo)}`);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'No se encontró información de trazabilidad');
        }

        const data = await response.json();
        renderizarTrazabilidad(data);
        
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('resultadosTrazabilidad').style.display = 'block';

    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = error.message;
    }
}

function renderizarTrazabilidad(datos) {
    const timelineContainer = document.getElementById('timelineContainer');
    const detallesContainer = document.getElementById('detallesContainer');
    
    timelineContainer.innerHTML = '';
    detallesContainer.innerHTML = '';

    // Configuración de etapas con nombres amigables
    const etapasConfig = {
        'campo': { 
            nombre: 'Cosecha', 
            icono: '🌱',
            descripcion: 'Origen del producto',
            campos: {
                'productor': 'Productor',
                'variedad': 'Variedad',
                'municipio': 'Ubicación',
                'superficie_ha': 'Superficie (ha)'
            }
        },
        'planta': { 
            nombre: 'Procesamiento', 
            icono: '🏭',
            descripcion: 'Planta procesadora',
            campos: {
                'planta': 'Planta',
                'rendimiento_pct': 'Rendimiento',
                'rendimiento': 'Rendimiento'
            }
        },
        'salida': { 
            nombre: 'Empaque', 
            icono: '📦',
            descripcion: 'Producto empaquetado',
            campos: {
                'sku': 'Producto',
                'peso_t': 'Peso (Toneladas)',
                'peso': 'Peso',
                'cantidad_envio_t': 'Cantidad Enviada'
            }
        },
        'orden_envio': { 
            nombre: 'Orden de Envío', 
            icono: '📋',
            descripcion: 'Planta → Almacén',
            campos: {
                'planta_origen': 'Planta Origen',
                'almacen_destino': 'Almacén Destino',
                'conductor': 'Conductor',
                'vehiculo': 'Vehículo',
                'cantidad': 'Cantidad',
                'prioridad': 'Prioridad'
            }
        },
        'envio': { 
            nombre: 'Transporte', 
            icono: '🚛',
            descripcion: 'En tránsito',
            campos: {
                'estado': 'Estado',
                'transportista': 'Transportista',
                'vehiculo': 'Vehículo',
                'cantidad_t': 'Cantidad (t)'
            }
        },
        'almacen': { 
            nombre: 'Almacén', 
            icono: '🏪',
            descripcion: 'Almacenamiento',
            campos: {
                'zona': 'Zona',
                'ubicacion': 'Ubicación',
                'cantidad_recibida': 'Cantidad Recibida',
                'estado_producto': 'Estado del Producto'
            }
        },
        'pedido': { 
            nombre: 'Pedido', 
            icono: '📄',
            descripcion: 'Cliente final',
            campos: {
                'cliente': 'Cliente',
                'info': 'Información'
            }
        }
    };

    // Orden de las etapas
    const ordenEtapas = ['campo', 'planta', 'salida', 'orden_envio', 'envio', 'almacen', 'pedido'];
    
    // Renderizar Timeline
    ordenEtapas.forEach(key => {
        if (!datos.etapas.hasOwnProperty(key)) return;
        
        const etapaData = datos.etapas[key];
        const config = etapasConfig[key];
        if (!config) return;

        // Procesar datos
        let data = null;
        let isEmpty = false;
        
        if (etapaData === null) {
            isEmpty = true;
        } else if (Array.isArray(etapaData)) {
            if (etapaData.length === 0) {
                isEmpty = true;
            } else {
                data = etapaData[0];
            }
        } else {
            data = etapaData;
        }

        if (isEmpty || !data || !data.codigo) return;

        // Crear paso de timeline
        const step = document.createElement('div');
        step.className = `timeline-step ${data.estado || 'completed'}`;
        step.innerHTML = `
            <div class="step-icon">${config.icono}</div>
            <div class="step-info">
                <div class="step-name">${config.nombre}</div>
                <div class="step-code">${data.codigo}</div>
                <div class="step-date">${formatDate(data.fecha)}</div>
            </div>
        `;
        timelineContainer.appendChild(step);

        // Crear tarjeta de detalle
        if (data.detalles) {
            const card = document.createElement('div');
            card.className = 'col-md-4 col-lg-3 mb-4';
            
            let detallesHtml = '';
            Object.entries(data.detalles).forEach(([k, v]) => {
                const label = config.campos[k] || formatLabel(k);
                if (v !== null && v !== undefined) {
                    detallesHtml += `
                        <div class="detail-item">
                            <span class="detail-label">${label}</span>
                            <span class="detail-value">${v}</span>
                        </div>
                    `;
                }
            });

            card.innerHTML = `
                <div class="detail-card card h-100">
                    <div class="card-header bg-${key} text-white">
                        <h5><span class="mr-2">${config.icono}</span>${config.nombre}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">${config.descripcion}</small>
                            <h5 class="mb-0">${data.codigo}</h5>
                        </div>
                        ${detallesHtml}
                        <div class="mt-3 text-right">
                            <small class="text-muted"><i class="fas fa-calendar"></i> ${formatDate(data.fecha)}</small>
                        </div>
                    </div>
                </div>
            `;
            detallesContainer.appendChild(card);
        }
    });

    if (timelineContainer.children.length === 0) {
        timelineContainer.innerHTML = '<div class="alert alert-warning">No se encontraron datos de trazabilidad para este elemento.</div>';
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    try {
        const date = new Date(dateStr);
        return date.toLocaleDateString('es-ES', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
    } catch {
        return dateStr;
    }
}

function formatLabel(key) {
    return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}
</script>
@endsection
