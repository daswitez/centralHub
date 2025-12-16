@extends('layouts.app')

@section('page_title', 'Envíos de Productores - Logística')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-tractor text-success mr-2"></i>Envíos de Productores
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-leaf mr-1"></i>Logística desde fincas y productores
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Logística</a></li>
                <li class="breadcrumb-item active">Envíos Productores</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- KPI Cards --}}
    <div class="row" id="kpiCards" style="display: none;">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3 id="totalEnvios">0</h3>
                    <p>Total Envíos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tractor"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3 id="enTransito">0</h3>
                    <p>En Tránsito</p>
                </div>
                <div class="icon">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3 id="entregados">0</h3>
                    <p>Entregados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3 id="tasaExito">0%</h3>
                    <p>Tasa de Éxito</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card card-outline card-success mb-3" id="filtrosCard" style="display: none;">
        <div class="card-body py-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-success active" data-filter="todos">
                        <i class="fas fa-list mr-1"></i>Todos
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-filter="transito">
                        <i class="fas fa-truck mr-1"></i>En Tránsito
                    </button>
                    <button type="button" class="btn btn-outline-info" data-filter="entregado">
                        <i class="fas fa-check mr-1"></i>Entregados
                    </button>
                </div>
                <div class="input-group input-group-sm" style="max-width: 280px;">
                    <input type="text" id="buscarEnvio" class="form-control" placeholder="Buscar productor...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-success text-white"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card principal --}}
    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-2"></i>Listado de Envíos de Productores
            </h3>
            <div class="card-tools">
                <button id="btnRecargar" class="btn btn-sm btn-success">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Loading --}}
            <div id="loading" class="p-5 text-center">
                <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-3 text-muted mb-0">
                    <i class="fas fa-cloud-download-alt mr-1"></i>Consultando envíos de productores...
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
                    <i class="fas fa-tractor fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin envíos de productores</h5>
                <p class="text-muted mb-0">No hay registros de envíos de productores</p>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="tablaDatos" class="table table-hover table-striped mb-0" style="display:none;">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 60px;">
                                <i class="fas fa-hashtag text-muted"></i>
                            </th>
                            <th>
                                <i class="fas fa-user text-muted mr-1"></i>Productor
                            </th>
                            <th>
                                <i class="fas fa-route text-muted mr-1"></i>Ruta
                            </th>
                            <th class="text-center">Estado</th>
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
            background-color: rgba(40, 167, 69, 0.05) !important;
            transform: translateX(3px);
        }
        .productor-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .ruta-visual {
            font-size: 0.85rem;
        }
        .ruta-arrow {
            color: #28a745;
            font-weight: bold;
            margin: 0 8px;
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
        .badge-estado {
            font-size: 0.85rem;
            padding: 0.5em 1em;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        let enviosData = [];
        let filtroActual = 'todos';

        const estadoConfig = {
            'Entregado': { color: 'success', icon: 'check-double', text: 'Entregado' },
            'En tránsito': { color: 'warning', icon: 'truck', text: 'En Tránsito' },
            'Pendiente': { color: 'secondary', icon: 'clock', text: 'Pendiente' },
            'default': { color: 'info', icon: 'shipping-fast', text: 'En Proceso' }
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
                const response = await fetch('/api/orgtrack/envios-productores', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const json = await response.json();
                enviosData = json?.data ?? [];

                loading.style.display = 'none';

                if (enviosData.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Actualizar KPIs
                const entregados = enviosData.filter(e => e.estado === 'Entregado').length;
                const enTransito = enviosData.filter(e => e.estado !== 'Entregado').length;
                const tasaExito = enviosData.length > 0 ? Math.round((entregados / enviosData.length) * 100) : 0;

                document.getElementById('totalEnvios').textContent = enviosData.length;
                document.getElementById('enTransito').textContent = enTransito;
                document.getElementById('entregados').textContent = entregados;
                document.getElementById('tasaExito').textContent = tasaExito + '%';

                kpiCards.style.display = 'flex';
                kpiCards.classList.add('animate-in');
                
                filtrosCard.style.display = 'block';
                filtrosCard.classList.add('animate-in');

                renderizarTabla(enviosData);

            } catch (e) {
                console.error('❌ Error:', e);
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

                tr.innerHTML = `
                    <td class="align-middle">
                        <span class="text-muted font-weight-bold">#${envio.id}</span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="productor-avatar mr-2">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <strong>${envio.nombre_remitente || '—'}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone mr-1"></i>${envio.telefono_remitente || '—'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="ruta-visual">
                            <span class="text-success">
                                <i class="fas fa-seedling mr-1"></i>${envio.nombre_origen || '—'}
                            </span>
                            <span class="ruta-arrow">→</span>
                            <span class="text-primary">
                                <i class="fas fa-warehouse mr-1"></i>${envio.nombre_destino || '—'}
                            </span>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge badge-pill badge-${estado.color} badge-estado">
                            <i class="fas fa-${estado.icon} mr-1"></i>
                            ${estado.text}
                        </span>
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