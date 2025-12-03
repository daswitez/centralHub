@extends('layouts.app')

@section('page_title', 'Trazabilidad')

@section('page_header')
    <div>
        <h1 class="m-0">Trazabilidad Completa</h1>
        <p class="text-muted mb-0">Seguimiento de productos desde el campo hasta el cliente</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Buscar Trazabilidad</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tipoBusqueda">Tipo de Búsqueda *</label>
                        <select id="tipoBusqueda" class="form-control" onchange="actualizarDropdown()">
                            <option value="campo" selected>Lote de Campo</option>
                            <option value="planta">Lote de Planta</option>
                            <option value="salida">Lote de Salida</option>
                            <option value="envio">Envío</option>
                            <option value="pedido">Pedido</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="codigoBusqueda">Seleccionar *</label>
                        
                        {{-- Dropdown para Lote Campo (visible por defecto) --}}
                        <select id="dropdown_campo" class="form-control dropdown-lote">
                            <option value="">Seleccione lote de campo...</option>
                            @foreach($lotesCampo as $lc)
                                <option value="{{ $lc->codigo_lote_campo }}">
                                    {{ $lc->codigo_lote_campo }} - 
                                    {{ \Carbon\Carbon::parse($lc->fecha_cosecha)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown para Lote Planta --}}
                        <select id="dropdown_planta" class="form-control dropdown-lote" style="display: none;">
                            <option value="">Seleccione lote de planta...</option>
                            @foreach($lotesPlanta as $lp)
                                <option value="{{ $lp->codigo_lote_planta }}">
                                    {{ $lp->codigo_lote_planta }} - 
                                    {{ \Carbon\Carbon::parse($lp->fecha_inicio)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown para Lote Salida --}}
                        <select id="dropdown_salida" class="form-control dropdown-lote" style="display: none;">
                            <option value="">Seleccione lote de salida...</option>
                            @foreach($lotesSalida as $ls)
                                <option value="{{ $ls->codigo_lote_salida }}">
                                    {{ $ls->codigo_lote_salida }} - 
                                    {{ \Carbon\Carbon::parse($ls->fecha_empaque)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown para Envío --}}
                        <select id="dropdown_envio" class="form-control dropdown-lote" style="display: none;">
                            <option value="">Seleccione envío...</option>
                            @foreach($envios as $env)
                                <option value="{{ $env->codigo_envio }}">
                                    {{ $env->codigo_envio }} - 
                                    {{ \Carbon\Carbon::parse($env->fecha_salida)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Dropdown para Pedido --}}
                        <select id="dropdown_pedido" class="form-control dropdown-lote" style="display: none;">
                            <option value="">Seleccione pedido...</option>
                            @foreach($pedidos as $ped)
                                <option value="{{ $ped->codigo_pedido }}">
                                    {{ $ped->codigo_pedido }} - 
                                    {{ \Carbon\Carbon::parse($ped->fecha_pedido)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" 
                                class="btn btn-primary btn-block" 
                                onclick="buscarTrazabilidad()">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Trazabilidad Completa:</strong> Al buscar un lote, se mostrará toda la cadena de producción:
                <ul class="mb-0 mt-2">
                    <li><strong>Campo</strong> → De dónde proviene el producto</li>
                    <li><strong>Planta</strong> → Dónde fue procesado</li>
                    <li><strong>Empaque</strong> → Cómo fue empacado (SKU, peso)</li>
                    <li><strong>Envío</strong> → Transporte logístico</li>
                    <li><strong>Pedido</strong> → A qué cliente fue vendido</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Resultados de Trazabilidad --}}
    <div id="resultadosTrazabilidad" style="display: none;">
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Flujo de Trazabilidad</h3>
            </div>
            <div class="card-body">
                {{-- Flow Diagram --}}
                <div id="flowDiagram" class="flow-container">
                    {{-- Se genera dinámicamente con JavaScript --}}
                </div>
            </div>
        </div>

        {{-- Detalles por Etapa --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles</h3>
            </div>
            <div class="card-body">
                <div id="detallesEtapas">
                    {{-- Se genera dinámicamente --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Spinner --}}
    <div id="loadingSpinner" class="text-center" style="display: none;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
        <p class="mt-2">Cargando trazabilidad...</p>
    </div>

    {{-- Error Alert --}}
    <div id="errorAlert" class="alert alert-danger" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="errorMessage"></span>
    </div>
@endsection

@push('styles')
<style>
    .flow-container {
        display: flex;
        align-items: center;
        padding: 2rem;
        overflow-x: auto;
        gap: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
    }

    .stage {
        min-width: 180px;
        padding: 1.5rem;
        border-radius: 12px;
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        position: relative;
        transition: all 0.3s ease;
    }

    .stage:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    }

    .stage::after {
        content: '→';
        position: absolute;
        right: -1.5rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2rem;
        color: white;
        font-weight: bold;
    }

    .stage:last-child::after {
        display: none;
    }

    .stage.completed {
        border-left: 5px solid #28a745;
    }

    .stage.pending {
        border-left: 5px solid #ffc107;
    }

    .stage.active {
        border-left: 5px solid #007bff;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        50% { box-shadow: 0 4px 20px rgba(0,123,255,0.5); }
    }

    .stage-icon {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .stage-name {
        font-weight: bold;
        font-size: 0.9rem;
        color: #333;
        text-align: center;
        margin-bottom: 0.3rem;
    }

    .stage-code {
        font-size: 0.85rem;
        color: #666;
        text-align: center;
        font-family: monospace;
    }

    .stage-date {
        font-size: 0.75rem;
        color: #999;
        text-align: center;
        margin-top: 0.3rem;
    }
</style>
@endpush

@push('scripts')
<script>
// Mostrar/ocultar dropdowns según el tipo seleccionado
function actualizarDropdown() {
    const tipo = document.getElementById('tipoBusqueda').value;
    
    // Ocultar todos los dropdowns
    document.querySelectorAll('.dropdown-lote').forEach(dropdown => {
        dropdown.style.display = 'none';
        dropdown.value = ''; // Reset value
    });
    
    // Mostrar el dropdown correspondiente
    if (tipo) {
        const dropdownActivo = document.getElementById(`dropdown_${tipo}`);
        if (dropdownActivo) {
            dropdownActivo.style.display = 'block';
        }
    }
}

async function buscarTrazabilidad() {
    const tipo = document.getElementById('tipoBusqueda').value;
    
    if (!tipo) {
        alert('Por favor seleccione un tipo de búsqueda');
        return;
    }
    
    // Obtener el dropdown activo y su valor
    const dropdownActivo = document.getElementById(`dropdown_${tipo}`);
    const codigo = dropdownActivo ? dropdownActivo.value : '';
    
    if (!codigo) {
        alert('Por favor seleccione una opción');
        return;
    }

    // Mostrar loading
    document.getElementById('loadingSpinner').style.display = 'block';
    document.getElementById('resultadosTrazabilidad').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';

    try {
        const response = await fetch(`/api/trazabilidad/${tipo}/${encodeURIComponent(codigo)}`);
        
        if (!response.ok) {
            throw new Error('No se encontró información de trazabilidad');
        }

        const data = await response.json();
        renderizarTrazabilidad(data);
        
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('resultadosTrazabilidad').style.display = 'block';

    } catch (error) {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorAlert').style.display = 'block';
        document.getElementById('errorMessage').textContent = error.message;
    }
}

function renderizarTrazabilidad(datos) {
    const container = document.getElementById('flowDiagram');
    container.innerHTML = '';

    const etapaConfig = {
        'campo': { nombre: 'Campo', icono: '🌱' },
        'planta': { nombre: 'Planta', icono: '🏭' },
        'salida': { nombre: 'Empaque', icono: '📦' },
        'envio': { nombre: 'Envío', icono: '🚛' },
        'pedido': { nombre: 'Pedido', icono: '📄' }
    };

    Object.entries(datos.etapas).forEach(([key, etapaData]) => {
        if (!etapaData) return;

        const config = etapaConfig[key];
        if (!config) return;

        // Si es array (múltiples), mostrar solo el primero en flow
        const data = Array.isArray(etapaData) ? etapaData[0] : etapaData;

        const div = document.createElement('div');
        div.className = `stage ${data.estado}`;
        div.innerHTML = `
            <div class="stage-icon">${config.icono}</div>
            <div class="stage-name">${config.nombre}</div>
            <div class="stage-code">${data.codigo}</div>
            <div class="stage-date">${formatDate(data.fecha)}</div>
        `;
        
        container.appendChild(div);
    });

    // Renderizar detalles
    renderizarDetalles(datos.etapas);
}

function renderizarDetalles(etapas) {
    const container = document.getElementById('detallesEtapas');
    container.innerHTML = '';

    Object.entries(etapas).forEach(([key, etapaData]) => {
        if (!etapaData) return;

        const dataArray = Array.isArray(etapaData) ? etapaData : [etapaData];

        dataArray.forEach((data, index) => {
            if (!data.detalles) return;

            const card = document.createElement('div');
            card.className = 'card mb-2';
            card.innerHTML = `
                <div class="card-header">
                    <h5 class="mb-0">${key.toUpperCase()}${dataArray.length > 1 ? ` #${index + 1}` : ''}: ${data.codigo}</h5>
                </div>
                <div class="card-body">
                    ${Object.entries(data.detalles).map(([k, v]) => `
                        <p class="mb-1"><strong>${k}:</strong> ${v ?? '-'}</p>
                    `).join('')}
                </div>
            `;
            container.appendChild(card);
        });
    });
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarDropdown();
});
</script>
@endpush
