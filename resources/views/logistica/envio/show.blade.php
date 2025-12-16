@extends('layouts.app')

@section('page_title', 'Detalle del Envío')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-shipping-fast text-primary mr-2"></i>Envío #{{ $envioId }}
            </h1>
            <p class="text-muted mb-0 mt-1" id="subtitulo">
                <i class="fas fa-truck mr-1"></i>Cargando información...
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('logistica.envios.index') }}">Envíos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- Loading --}}
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Cargando...</span>
        </div>
        <p class="mt-3 text-muted mb-0">
            <i class="fas fa-cloud-download-alt mr-1"></i>Obteniendo detalles del envío...
        </p>
    </div>

    {{-- Error --}}
    <div id="error" class="text-center py-5" style="display:none;">
        <div class="text-danger mb-3">
            <i class="fas fa-exclamation-circle fa-4x"></i>
        </div>
        <h5 class="text-danger">Error al cargar envío</h5>
        <p id="errorMessage" class="text-muted mb-3"></p>
        <a href="{{ route('logistica.envios.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-1"></i>Volver al listado
        </a>
    </div>

    {{-- Contenido Principal --}}
    <div id="contenido" style="display:none;">

        {{-- Header con estado destacado --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-gradient-light">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center">
                                <div class="envio-icon mr-3">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">Envío <span id="numeroEnvio" class="text-primary">#{{ $envioId }}</span></h4>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt mr-1"></i>Creado: <span id="fechaCreacionHeader"></span>
                                    </small>
                                </div>
                            </div>
                            <div id="estadoBadge" class="badge badge-pill px-4 py-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Información del Envío --}}
        <div class="card card-outline card-primary mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Información del Envío
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr class="border-bottom">
                            <td style="width: 50px;" class="text-center align-middle">
                                <div class="icon-cell bg-primary">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted d-block">Cliente / Remitente</small>
                                <strong id="clienteInfo"></strong>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-center align-middle">
                                <div class="icon-cell bg-success">
                                    <i class="fas fa-map-pin text-white"></i>
                                </div>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted d-block">Origen</small>
                                <strong id="origenInfo"></strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle">
                                <div class="icon-cell bg-danger">
                                    <i class="fas fa-flag-checkered text-white"></i>
                                </div>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted d-block">Destino</small>
                                <strong id="destinoInfo"></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Mapa --}}
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-2"></i>Ubicación de Destino
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <iframe 
                            id="mapa_frame" 
                            width="100%" 
                            height="350" 
                            frameborder="0" 
                            style="border:0; border-radius: 0 0 0.25rem 0.25rem;" 
                            allowfullscreen 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>

                {{-- Particiones / Asignaciones --}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-2"></i>Particiones y Asignaciones
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-info" id="totalParticiones">0</span>
                        </div>
                    </div>
                    <div class="card-body" id="particiones">
                        {{-- Particiones dinámicas --}}
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Timeline de Ruta --}}
                <div class="card">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="fas fa-route mr-2"></i>Ruta del Envío
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline" id="timelineRuta">
                            {{-- Timeline items dinámicos --}}
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-2"></i>Acciones
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('logistica.envios.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-arrow-left mr-2 text-muted"></i>
                                Volver al listado
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" onclick="window.print(); return false;">
                                <i class="fas fa-print mr-2 text-muted"></i>
                                Imprimir detalle
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Resumen rápido --}}
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle mr-1"></i>Información del Envío
                        </h6>
                        <ul class="list-unstyled mb-0" id="resumenRapido">
                            {{-- Datos dinámicos --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estilos --}}
    <style>
        .envio-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        .icon-cell {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .particion-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .particion-card:hover {
            transform: translateX(5px);
        }
        .particion-card.entregado {
            border-left-color: #28a745;
        }
        .transportista-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .carga-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-left: 3px solid #17a2b8;
        }
        .checklist-item {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .checklist-item:last-child {
            border-bottom: none;
        }
        .firma-container {
            background: white;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeIn 0.4s ease forwards;
        }
        @media print {
            .sidebar, .navbar, .card-tools, .list-group {
                display: none !important;
            }
        }
    </style>

    {{-- JavaScript --}}
    <script>
        const estadoConfig = {
            'Entregado': { color: 'success', icon: 'check-double', text: 'Entregado' },
            'En tránsito': { color: 'warning', icon: 'truck', text: 'En Tránsito' },
            'Pendiente': { color: 'secondary', icon: 'clock', text: 'Pendiente' },
            'default': { color: 'info', icon: 'shipping-fast', text: 'En Proceso' }
        };

        async function cargarDetalle() {
            try {
                const response = await fetch(
                    `/api/orgtrack/envios/{{ $envioId }}/documento`,
                    { headers: { 'Accept': 'application/json' } }
                );

                const json = await response.json();

                if (!response.ok || json.success === false) {
                    throw new Error(json.error ?? 'Error desconocido');
                }

                const data = json.data;
                const estadoCfg = estadoConfig[data.estado] || estadoConfig['default'];

                // Subtítulo
                document.getElementById('subtitulo').innerHTML = 
                    `<i class="fas fa-truck mr-1"></i>Detalle completo y trazabilidad`;

                // Header
                document.getElementById('fechaCreacionHeader').textContent = 
                    new Date(data.fecha_creacion).toLocaleDateString('es-ES', {
                        day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                    });

                // Estado badge
                const estadoBadge = document.getElementById('estadoBadge');
                estadoBadge.className = `badge badge-pill badge-${estadoCfg.color} px-4 py-3`;
                estadoBadge.innerHTML = `<i class="fas fa-${estadoCfg.icon} mr-2"></i>${estadoCfg.text}`;

                // Info boxes
                document.getElementById('clienteInfo').textContent = data.nombre_cliente ?? '—';
                document.getElementById('origenInfo').textContent = data.nombre_origen ?? '—';
                document.getElementById('destinoInfo').textContent = data.nombre_destino ?? '—';

                // Mapa
                if (data.nombre_destino) {
                    const cleanDest = encodeURIComponent(data.nombre_destino);
                    document.getElementById('mapa_frame').src = 
                        `https://maps.google.com/maps?q=${cleanDest}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
                }

                // Timeline de ruta
                const timeline = document.getElementById('timelineRuta');
                timeline.innerHTML = `
                    <div class="time-label">
                        <span class="bg-success">Origen</span>
                    </div>
                    <div>
                        <i class="fas fa-map-pin bg-success"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header text-success">Punto de Recogida</h3>
                            <div class="timeline-body">
                                ${data.nombre_origen || '—'}
                            </div>
                        </div>
                    </div>
                    <div class="time-label">
                        <span class="bg-info">Tránsito</span>
                    </div>
                    <div>
                        <i class="fas fa-truck bg-info"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header">En Camino</h3>
                            <div class="timeline-body">
                                Estado actual: <strong>${estadoCfg.text}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="time-label">
                        <span class="bg-${data.estado === 'Entregado' ? 'success' : 'secondary'}">Destino</span>
                    </div>
                    <div>
                        <i class="fas fa-flag-checkered bg-${data.estado === 'Entregado' ? 'success' : 'secondary'}"></i>
                        <div class="timeline-item">
                            <h3 class="timeline-header ${data.estado === 'Entregado' ? 'text-success' : ''}">
                                ${data.estado === 'Entregado' ? '✓ Entregado' : 'Pendiente de Entrega'}
                            </h3>
                            <div class="timeline-body">
                                ${data.nombre_destino || '—'}
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                `;

                // Resumen rápido
                document.getElementById('resumenRapido').innerHTML = `
                    <li class="mb-2">
                        <i class="fas fa-hashtag text-muted mr-2"></i>
                        <strong>ID:</strong> ${data.id_envio}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-muted mr-2"></i>
                        <strong>Fecha:</strong> ${new Date(data.fecha_creacion).toLocaleDateString('es-ES')}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-boxes text-muted mr-2"></i>
                        <strong>Particiones:</strong> ${data.particiones?.length || 0}
                    </li>
                    <li>
                        <i class="fas fa-info-circle text-muted mr-2"></i>
                        <strong>Estado:</strong> 
                        <span class="badge badge-${estadoCfg.color}">${estadoCfg.text}</span>
                    </li>
                `;

                // Particiones
                document.getElementById('totalParticiones').textContent = data.particiones?.length || 0;
                const contParticiones = document.getElementById('particiones');
                contParticiones.innerHTML = '';

                if (!data.particiones || data.particiones.length === 0) {
                    contParticiones.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mb-0">No hay particiones registradas</p>
                        </div>
                    `;
                } else {
                    data.particiones.forEach((p, index) => {
                        const esEntregado = p.estado === 'Entregado';
                        const inicialTransp = p.transportista?.nombre?.charAt(0)?.toUpperCase() || '?';

                        // Cargas
                        const cargasHtml = p.cargas.map(c => `
                            <div class="carga-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${c.tipo}</strong> - ${c.variedad}
                                    </div>
                                    <div>
                                        <span class="badge badge-info mr-1">${c.cantidad} uds</span>
                                        <span class="badge badge-secondary">${c.peso} kg</span>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        // Condiciones
                        const condicionesHtml = p.checklistCondiciones.map(c => `
                            <div class="checklist-item d-flex justify-content-between">
                                <span>${c.condicion.titulo}</span>
                                ${c.cumple === null 
                                    ? '<span class="text-muted">—</span>' 
                                    : c.cumple 
                                        ? '<span class="text-success"><i class="fas fa-check-circle"></i></span>'
                                        : '<span class="text-danger"><i class="fas fa-times-circle"></i></span>'
                                }
                            </div>
                        `).join('');

                        // Incidentes
                        const incidentesHtml = p.checklistIncidentes.map(i => `
                            <div class="checklist-item">
                                <div class="d-flex justify-content-between">
                                    <span>${i.tipo_incidente.titulo}</span>
                                    ${i.ocurrio 
                                        ? '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
                                        : '<span class="text-success"><i class="fas fa-check"></i></span>'
                                    }
                                </div>
                                ${i.ocurrio && i.descripcion ? `<small class="text-danger">${i.descripcion}</small>` : ''}
                            </div>
                        `).join('');

                        contParticiones.innerHTML += `
                            <div class="card particion-card mb-3 ${esEntregado ? 'entregado' : ''} animate-in" style="animation-delay: ${index * 0.1}s">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-cube mr-2"></i>
                                        <strong>Asignación #${p.id_asignacion}</strong>
                                    </div>
                                    <span class="badge badge-${esEntregado ? 'success' : 'info'}">${p.estado}</span>
                                </div>
                                <div class="card-body">
                                    {{-- Transportista y Vehículo --}}
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <div class="transportista-avatar mr-3">
                                                    ${inicialTransp}
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">${p.transportista.nombre} ${p.transportista.apellido}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone mr-1"></i>${p.transportista.telefono}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bg-light rounded p-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-truck fa-2x text-info mr-3"></i>
                                                    <div>
                                                        <strong>${p.vehiculo.tipo}</strong>
                                                        <br>
                                                        <span class="badge badge-dark">${p.vehiculo.placa}</span>
                                                        <span class="badge badge-secondary">${p.tipo_transporte.nombre}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Cargas --}}
                                    <h6 class="mb-2"><i class="fas fa-boxes mr-2 text-info"></i>Cargas</h6>
                                    ${cargasHtml || '<p class="text-muted">Sin cargas registradas</p>'}

                                    <div class="row mt-4">
                                        {{-- Condiciones --}}
                                        <div class="col-md-6">
                                            <h6 class="mb-2"><i class="fas fa-clipboard-check mr-2 text-success"></i>Condiciones</h6>
                                            <div class="bg-light rounded p-3">
                                                ${condicionesHtml || '<p class="text-muted mb-0">Sin checklist</p>'}
                                            </div>
                                        </div>
                                        {{-- Incidentes --}}
                                        <div class="col-md-6">
                                            <h6 class="mb-2"><i class="fas fa-exclamation-triangle mr-2 text-warning"></i>Incidentes</h6>
                                            <div class="bg-light rounded p-3">
                                                ${incidentesHtml || '<p class="text-muted mb-0">Sin incidentes</p>'}
                                            </div>
                                        </div>
                                    </div>

                                    ${p.firmaTransportista ? `
                                        <div class="mt-4">
                                            <h6 class="mb-2"><i class="fas fa-signature mr-2 text-primary"></i>Firma del Transportista</h6>
                                            <div class="firma-container">
                                                <img src="${p.firmaTransportista}" class="img-fluid" style="max-height: 100px;" alt="Firma">
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });
                }

                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';
                document.getElementById('contenido').classList.add('animate-in');

            } catch (e) {
                console.error(e);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo cargar el detalle del envío';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDetalle);
    </script>
@endsection