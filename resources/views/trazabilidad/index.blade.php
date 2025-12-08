@extends('layouts.app')

@section('page_title', 'Trazabilidad')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-route text-primary"></i> Trazabilidad de Productos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Trazabilidad</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

{{-- Card de Búsqueda --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-search mr-2"></i>Buscar Trazabilidad</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label><i class="fas fa-filter mr-1"></i>¿Qué deseas rastrear?</label>
                    <select id="tipoBusqueda" class="form-control select2" style="width: 100%;" onchange="actualizarDropdown()">
                        <option value="campo">🌱 Lote de Campo (Cosecha)</option>
                        <option value="planta">🏭 Lote de Planta (Procesamiento)</option>
                        <option value="salida">📦 Lote de Salida (Empaque)</option>
                        <option value="orden_envio">🚛 Orden de Envío (Planta → Almacén)</option>
                        <option value="envio">📍 Envío (Transporte)</option>
                        <option value="pedido">📄 Pedido (Cliente)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label><i class="fas fa-barcode mr-1"></i>Seleccionar Código</label>
                    
                    {{-- Dropdowns dinámicos --}}
                    <select id="dropdown_campo" class="form-control select2 dropdown-lote" style="width: 100%;">
                        <option value="">-- Seleccione lote de campo --</option>
                        @foreach($lotesCampo as $lc)
                            <option value="{{ $lc->codigo_lote_campo }}">
                                {{ $lc->codigo_lote_campo }} ({{ \Carbon\Carbon::parse($lc->fecha_cosecha)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    <select id="dropdown_planta" class="form-control select2 dropdown-lote" style="width: 100%; display: none;">
                        <option value="">-- Seleccione lote de planta --</option>
                        @foreach($lotesPlanta as $lp)
                            <option value="{{ $lp->codigo_lote_planta }}">
                                {{ $lp->codigo_lote_planta }} ({{ \Carbon\Carbon::parse($lp->fecha_inicio)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    <select id="dropdown_salida" class="form-control select2 dropdown-lote" style="width: 100%; display: none;">
                        <option value="">-- Seleccione lote de salida --</option>
                        @foreach($lotesSalida as $ls)
                            <option value="{{ $ls->codigo_lote_salida }}">
                                {{ $ls->codigo_lote_salida }} ({{ \Carbon\Carbon::parse($ls->fecha_empaque)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    <select id="dropdown_orden_envio" class="form-control select2 dropdown-lote" style="width: 100%; display: none;">
                        <option value="">-- Seleccione orden de envío --</option>
                        @foreach($ordenesEnvio ?? [] as $oe)
                            <option value="{{ $oe->codigo_orden }}">
                                {{ $oe->codigo_orden }} ({{ \Carbon\Carbon::parse($oe->fecha_creacion)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    <select id="dropdown_envio" class="form-control select2 dropdown-lote" style="width: 100%; display: none;">
                        <option value="">-- Seleccione envío --</option>
                        @foreach($envios as $env)
                            <option value="{{ $env->codigo_envio }}">
                                {{ $env->codigo_envio }} ({{ \Carbon\Carbon::parse($env->fecha_salida)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>

                    <select id="dropdown_pedido" class="form-control select2 dropdown-lote" style="width: 100%; display: none;">
                        <option value="">-- Seleccione pedido --</option>
                        @foreach($pedidos as $ped)
                            <option value="{{ $ped->codigo_pedido }}">
                                {{ $ped->codigo_pedido }} ({{ \Carbon\Carbon::parse($ped->fecha_pedido)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-primary btn-block btn-lg" onclick="buscarTrazabilidad()">
                        <i class="fas fa-search mr-2"></i> Rastrear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loadingOverlay" style="display: none;">
    <div class="overlay dark">
        <i class="fas fa-3x fa-sync-alt fa-spin"></i>
        <p class="mt-3">Buscando trazabilidad...</p>
    </div>
</div>

{{-- Alerta de Error --}}
<div id="errorAlert" class="alert alert-danger alert-dismissible" style="display: none;">
    <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
    <h5><i class="icon fas fa-ban"></i> Error</h5>
    <span id="errorMessage"></span>
</div>

{{-- Resultados --}}
<div id="resultadosTrazabilidad" style="display: none;">
    
    {{-- Resumen Rápido --}}
    <div class="row" id="resumenRapido"></div>
    
    {{-- Timeline de Trazabilidad --}}
    <div class="card">
        <div class="card-header bg-gradient-primary d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-stream mr-2"></i>Cadena de Trazabilidad</h3>
            <button id="btnExportarPdf" class="btn btn-sm btn-light" onclick="exportarPdf()">
                <i class="fas fa-file-pdf text-danger"></i> Exportar PDF
            </button>
        </div>
        <div class="card-body">
            <div class="timeline" id="timelineContainer">
                {{-- Timeline items se agregan dinámicamente --}}
            </div>
        </div>
    </div>
    
    {{-- Tarjetas de Detalle --}}
    <div class="row" id="detallesCards"></div>
</div>


{{-- Estilos adicionales --}}
<style>
/* Timeline AdminLTE personalizado */
.timeline {
    position: relative;
    margin: 0;
    padding: 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 31px;
    width: 4px;
    background: linear-gradient(180deg, #007bff, #28a745, #ffc107, #dc3545);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-left: 60px;
    margin-bottom: 25px;
}

.timeline-item > .timeline-icon {
    position: absolute;
    left: -60px;
    top: 0;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    text-align: center;
    font-size: 1.5rem;
    line-height: 50px;
    background: #007bff;
    color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 1;
}

.timeline-item.completed > .timeline-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.timeline-item.pending > .timeline-icon {
    background: linear-gradient(135deg, #6c757d, #adb5bd);
}

.timeline-item.en_ruta > .timeline-icon {
    background: linear-gradient(135deg, #007bff, #17a2b8);
}

.timeline-item > .timeline-panel {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    padding: 20px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.timeline-item > .timeline-panel:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.timeline-item > .timeline-panel::before {
    content: '';
    position: absolute;
    left: -15px;
    top: 15px;
    border-width: 8px;
    border-style: solid;
    border-color: transparent #fff transparent transparent;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #343a40;
    margin: 0;
}

.timeline-code {
    font-family: 'Courier New', monospace;
    background: #e9ecef;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
}

.timeline-body {
    color: #6c757d;
}

.timeline-body .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid #f1f3f4;
}

.timeline-body .detail-row:last-child {
    border-bottom: none;
}

.timeline-footer {
    margin-top: 10px;
    font-size: 0.85rem;
    color: #adb5bd;
}

/* Animaciones */
.timeline-item {
    animation: slideInLeft 0.5s ease forwards;
    opacity: 0;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.timeline-item:nth-child(1) { animation-delay: 0.1s; }
.timeline-item:nth-child(2) { animation-delay: 0.2s; }
.timeline-item:nth-child(3) { animation-delay: 0.3s; }
.timeline-item:nth-child(4) { animation-delay: 0.4s; }
.timeline-item:nth-child(5) { animation-delay: 0.5s; }
.timeline-item:nth-child(6) { animation-delay: 0.6s; }
.timeline-item:nth-child(7) { animation-delay: 0.7s; }

/* Small boxes para resumen */
.info-box-sm {
    min-height: 80px;
    padding: 15px;
}

/* Loading overlay */
#loadingOverlay .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    z-index: 9999;
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
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
        Swal.fire({
            icon: 'warning',
            title: 'Selección requerida',
            text: 'Por favor seleccione un elemento para rastrear'
        });
        return;
    }

    // Mostrar loading
    document.getElementById('loadingOverlay').style.display = 'block';
    document.getElementById('resultadosTrazabilidad').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';

    try {
        const response = await fetch(`/api/trazabilidad/${tipo}/${encodeURIComponent(codigo)}`);
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'No se encontró información de trazabilidad');
        }

        const data = await response.json();
        
        // Guardar última búsqueda para exportar PDF
        ultimaBusqueda = { tipo: tipo, codigo: codigo };
        
        renderizarTrazabilidad(data);
        
        document.getElementById('loadingOverlay').style.display = 'none';
        document.getElementById('resultadosTrazabilidad').style.display = 'block';


    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loadingOverlay').style.display = 'none';
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = error.message;
    }
}

function renderizarTrazabilidad(datos) {
    const timelineContainer = document.getElementById('timelineContainer');
    const detallesCards = document.getElementById('detallesCards');
    const resumenRapido = document.getElementById('resumenRapido');
    
    timelineContainer.innerHTML = '';
    detallesCards.innerHTML = '';
    resumenRapido.innerHTML = '';

    // Configuración de etapas
    const etapasConfig = {
        'campo': { nombre: 'Cosecha', icono: '🌱', color: 'success', bgClass: 'bg-success' },
        'planta': { nombre: 'Procesamiento', icono: '🏭', color: 'purple', bgClass: 'bg-purple' },
        'salida': { nombre: 'Empaque', icono: '📦', color: 'warning', bgClass: 'bg-warning' },
        'orden_envio': { nombre: 'Orden de Envío', icono: '📋', color: 'info', bgClass: 'bg-info' },
        'envio': { nombre: 'Transporte', icono: '🚛', color: 'primary', bgClass: 'bg-primary' },
        'almacen': { nombre: 'Almacén', icono: '🏪', color: 'teal', bgClass: 'bg-teal' },
        'pedido': { nombre: 'Pedido', icono: '📄', color: 'danger', bgClass: 'bg-danger' }
    };

    const ordenEtapas = ['campo', 'planta', 'salida', 'orden_envio', 'envio', 'almacen', 'pedido'];
    let etapasEncontradas = 0;

    // Generar Timeline y Cards
    ordenEtapas.forEach((key, index) => {
        if (!datos.etapas.hasOwnProperty(key)) return;
        
        const etapaData = datos.etapas[key];
        const config = etapasConfig[key];
        if (!config) return;

        let data = null;
        if (etapaData === null) return;
        if (Array.isArray(etapaData)) {
            if (etapaData.length === 0) return;
            data = etapaData[0];
        } else {
            data = etapaData;
        }

        if (!data || !data.codigo) return;
        
        etapasEncontradas++;

        // Timeline Item
        const timelineItem = document.createElement('div');
        timelineItem.className = `timeline-item ${data.estado || 'completed'}`;
        
        let detallesHtml = '';
        if (data.detalles) {
            Object.entries(data.detalles).forEach(([k, v]) => {
                if (v !== null && v !== undefined && v !== 'N/A') {
                    const label = formatLabel(k);
                    detallesHtml += `
                        <div class="detail-row">
                            <span class="text-muted">${label}</span>
                            <strong>${v}</strong>
                        </div>
                    `;
                }
            });
        }

        timelineItem.innerHTML = `
            <div class="timeline-icon">${config.icono}</div>
            <div class="timeline-panel">
                <div class="timeline-header">
                    <h4 class="timeline-title">${config.nombre}</h4>
                    <span class="timeline-code badge ${config.bgClass}">${data.codigo}</span>
                </div>
                <div class="timeline-body">
                    ${detallesHtml || '<span class="text-muted">Sin detalles adicionales</span>'}
                </div>
                <div class="timeline-footer">
                    <i class="fas fa-calendar-alt mr-1"></i> ${formatDate(data.fecha)}
                </div>
            </div>
        `;
        timelineContainer.appendChild(timelineItem);
    });

    // Mostrar resumen rápido
    resumenRapido.innerHTML = `
        <div class="col-12 mb-3">
            <div class="callout callout-info">
                <h5><i class="fas fa-info-circle mr-2"></i>Resumen de Trazabilidad</h5>
                <p class="mb-0">Se encontraron <strong>${etapasEncontradas}</strong> etapas en la cadena de trazabilidad.</p>
            </div>
        </div>
    `;

    if (etapasEncontradas === 0) {
        timelineContainer.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                No se encontraron datos de trazabilidad para este elemento.
            </div>
        `;
    }
}

function formatDate(dateStr) {
    if (!dateStr) return 'Sin fecha';
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
    const labels = {
        'productor': 'Productor',
        'telefono_productor': 'Teléfono',
        'variedad': 'Variedad',
        'aptitud': 'Aptitud',
        'ubicacion': 'Ubicación',
        'superficie': 'Superficie',
        'peso_cosechado': 'Peso Cosechado',
        'fecha_siembra': 'Fecha Siembra',
        'planta': 'Planta',
        'rendimiento': 'Rendimiento',
        'fecha_inicio': 'Fecha Inicio',
        'fecha_fin': 'Fecha Fin',
        'producto': 'Producto',
        'peso_neto': 'Peso Neto',
        'lote_origen': 'Lote Origen',
        'fecha_empaque': 'Fecha Empaque',
        'fecha_vencimiento': 'Vencimiento',
        'planta_origen': 'Planta Origen',
        'almacen_destino': 'Almacén Destino',
        'conductor': 'Conductor',
        'vehiculo': 'Vehículo',
        'cantidad': 'Cantidad',
        'prioridad': 'Prioridad',
        'estado_envio': 'Estado',
        'fecha_salida': 'Fecha Salida',
        'almacen': 'Almacén',
        'zona': 'Zona',
        'fecha_recepcion': 'Fecha Recepción',
        'observaciones': 'Observaciones'
    };
    return labels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

// Variables para guardar la última búsqueda
let ultimaBusqueda = { tipo: '', codigo: '' };

// Función para exportar PDF
function exportarPdf() {
    if (!ultimaBusqueda.tipo || !ultimaBusqueda.codigo) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin datos',
            text: 'Primero realice una búsqueda de trazabilidad'
        });
        return;
    }
    
    // Abrir en nueva pestaña la URL del PDF
    window.open(`/trazabilidad/pdf/${ultimaBusqueda.tipo}/${encodeURIComponent(ultimaBusqueda.codigo)}`, '_blank');
}
</script>

@endsection
