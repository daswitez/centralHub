@extends('layouts.app')

@section('page_title', 'Envíos - Logística')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-shipping-fast text-primary mr-2"></i>Gestión de Envíos
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-truck mr-1"></i>Seguimiento y control de envíos
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Logística</a></li>
                <li class="breadcrumb-item active">Envíos</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- KPI Cards --}}
    <div class="row" id="kpiCards" style="display: none;">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3 id="totalEnvios">0</h3>
                    <p>Total Envíos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3 id="enviosPendientes">0</h3>
                    <p>En Tránsito</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3 id="enviosEntregados">0</h3>
                    <p>Entregados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3 id="tasaEntrega">0%</h3>
                    <p>Tasa de Entrega</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card card-outline card-info mb-3" id="filtrosCard" style="display: none;">
        <div class="card-body py-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-info active" data-filter="todos">
                        <i class="fas fa-list mr-1"></i>Todos
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-filter="transito">
                        <i class="fas fa-truck mr-1"></i>En Tránsito
                    </button>
                    <button type="button" class="btn btn-outline-success" data-filter="entregado">
                        <i class="fas fa-check mr-1"></i>Entregados
                    </button>
                </div>
                <div class="input-group input-group-sm" style="max-width: 280px;">
                    <input type="text" id="buscarEnvio" class="form-control" placeholder="Buscar por remitente...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla Principal --}}
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-2"></i>Listado de Envíos
            </h3>
            <div class="card-tools">
                <button id="btnRecargar" class="btn btn-sm btn-info">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Loading --}}
            <div id="loading" class="p-5 text-center">
                <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-3 text-muted mb-0">
                    <i class="fas fa-cloud-download-alt mr-1"></i>Consultando envíos...
                </p>
            </div>

            {{-- Error --}}
            <div id="error" class="p-5 text-center" style="display:none;">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h5 class="text-danger">Error de conexión</h5>
                <p id="errorMessage" class="text-muted mb-3"></p>
                <button class="btn btn-outline-danger btn-sm" onclick="cargarDatos()">
                    <i class="fas fa-redo mr-1"></i>Reintentar
                </button>
            </div>

            {{-- Empty --}}
            <div id="empty" class="p-5 text-center" style="display:none;">
                <div class="text-muted mb-3">
                    <i class="fas fa-truck fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin envíos</h5>
                <p class="text-muted mb-0">No hay envíos registrados en el sistema</p>
            </div>

            {{-- Tabla mejorada --}}
            <div class="table-responsive">
                <table id="tablaDatos" class="table table-hover table-striped mb-0" style="display:none;">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 60px;">
                                <i class="fas fa-hashtag text-muted"></i>
                            </th>
                            <th>
                                <i class="fas fa-user text-muted mr-1"></i>Remitente
                            </th>
                            <th>
                                <i class="fas fa-map-marker-alt text-muted mr-1"></i>Ruta
                            </th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDatos"></tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Estilos --}}
    <style>
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(23, 162, 184, 0.05) !important;
            transform: translateX(3px);
        }
        .remitente-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .ruta-info {
            font-size: 0.85rem;
        }
        .ruta-arrow {
            color: #17a2b8;
            font-weight: bold;
        }
        .small-box {
            transition: all 0.3s ease;
        }
        .small-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        const ENVIO_SHOW_URL = "{{ route('logistica.envios.show', ':id') }}";
        let enviosData = [];
        let filtroActual = 'todos';

        const estadoConfig = {
            'Entregado': { color: 'success', icon: 'check-double' },
            'En tránsito': { color: 'warning', icon: 'truck' },
            'Pendiente': { color: 'secondary', icon: 'clock' },
            'default': { color: 'info', icon: 'shipping-fast' }
        };

        async function cargarDatos() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tabla = document.getElementById('tablaDatos');
            const tbody = document.getElementById('tbodyDatos');
            const kpiCards = document.getElementById('kpiCards');
            const filtrosCard = document.getElementById('filtrosCard');

            // Reset UI
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tabla.style.display = 'none';
            kpiCards.style.display = 'none';
            filtrosCard.style.display = 'none';
            tbody.innerHTML = '';

            try {
                const response = await fetch('/api/orgtrack/envios/all', {
                    headers: { 'Accept': 'application/json' }
                });

                const json = await response.json();

                if (!response.ok || json.success === false) {
                    throw new Error(json.error ?? 'Error en API');
                }

                enviosData = json.data ?? [];

                loading.style.display = 'none';

                if (!enviosData.length) {
                    empty.style.display = 'block';
                    return;
                }

                // Actualizar KPIs
                const entregados = enviosData.filter(e => e.estado === 'Entregado').length;
                const enTransito = enviosData.filter(e => e.estado !== 'Entregado').length;
                const tasaEntrega = enviosData.length > 0 ? Math.round((entregados / enviosData.length) * 100) : 0;

                document.getElementById('totalEnvios').textContent = enviosData.length;
                document.getElementById('enviosPendientes').textContent = enTransito;
                document.getElementById('enviosEntregados').textContent = entregados;
                document.getElementById('tasaEntrega').textContent = tasaEntrega + '%';

                kpiCards.style.display = 'flex';
                kpiCards.classList.add('animate-in');
                
                filtrosCard.style.display = 'block';
                filtrosCard.classList.add('animate-in');

                renderizarTabla(enviosData);

            } catch (e) {
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo conectar con el servicio de logística';
            }
        }

        function renderizarTabla(envios) {
            const tbody = document.getElementById('tbodyDatos');
            const tabla = document.getElementById('tablaDatos');
            tbody.innerHTML = '';

            envios.forEach((envio, index) => {
                const tr = document.createElement('tr');
                tr.className = 'animate-in';
                tr.style.animationDelay = `${index * 0.03}s`;

                const estado = estadoConfig[envio.estado] || estadoConfig['default'];
                const inicial = envio.nombre_remitente?.charAt(0)?.toUpperCase() || '?';
                const esEntregado = envio.estado === 'Entregado';

                tr.innerHTML = `
                    <td class="align-middle">
                        <span class="text-muted font-weight-bold">#${envio.id}</span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="remitente-avatar mr-2">
                                ${inicial}
                            </div>
                            <div>
                                <strong>${envio.nombre_remitente ?? '—'}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone mr-1"></i>${envio.telefono_remitente ?? '—'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="ruta-info">
                            <i class="fas fa-map-pin text-success mr-1"></i>
                            ${envio.direccion_origen ?? '—'}
                            <br>
                            <span class="ruta-arrow">↓</span>
                            <br>
                            <i class="fas fa-flag-checkered text-danger mr-1"></i>
                            ${envio.direccion_destino ?? '—'}
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge badge-pill badge-${estado.color} px-3 py-2">
                            <i class="fas fa-${estado.icon} mr-1"></i>
                            ${envio.estado}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        ${esEntregado
                            ? `<a href="${ENVIO_SHOW_URL.replace(':id', envio.id)}"
                                   class="btn btn-sm btn-primary" title="Ver detalle">
                                   <i class="fas fa-eye"></i>
                               </a>`
                            : `<span class="text-muted">—</span>`
                        }
                    </td>
                `;

                tbody.appendChild(tr);
            });

            tabla.style.display = 'table';
        }

        function filtrarEnvios(filtro) {
            filtroActual = filtro;
            let enviosFiltrados = enviosData;

            if (filtro === 'entregado') {
                enviosFiltrados = enviosData.filter(e => e.estado === 'Entregado');
            } else if (filtro === 'transito') {
                enviosFiltrados = enviosData.filter(e => e.estado !== 'Entregado');
            }

            // Aplicar búsqueda
            const busqueda = document.getElementById('buscarEnvio').value.toLowerCase();
            if (busqueda) {
                enviosFiltrados = enviosFiltrados.filter(e => 
                    e.nombre_remitente?.toLowerCase().includes(busqueda)
                );
            }

            if (enviosFiltrados.length === 0) {
                document.getElementById('tablaDatos').style.display = 'none';
                document.getElementById('empty').style.display = 'block';
            } else {
                document.getElementById('empty').style.display = 'none';
                renderizarTabla(enviosFiltrados);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            cargarDatos();
            
            document.getElementById('btnRecargar')?.addEventListener('click', cargarDatos);

            // Filtros
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filtrarEnvios(this.dataset.filter);
                });
            });

            // Búsqueda
            document.getElementById('buscarEnvio')?.addEventListener('input', function() {
                filtrarEnvios(filtroActual);
            });
        });
    </script>
@endsection