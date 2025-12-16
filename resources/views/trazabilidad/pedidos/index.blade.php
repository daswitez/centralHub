@extends('layouts.app')

@section('page_title', 'Pedidos - Logística')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>Gestión de Pedidos
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-truck mr-1"></i>Logística y seguimiento de pedidos
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Trazabilidad</a></li>
                <li class="breadcrumb-item active">Pedidos</li>
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
                    <h3 id="totalPedidos">0</h3>
                    <p>Total Pedidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3 id="pedidosPendientes">0</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3 id="pedidosAlmacenado">0</h3>
                    <p>Almacenados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3 id="pedidosEntregado">0</h3>
                    <p>Entregados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
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
                    <button type="button" class="btn btn-outline-warning" data-filter="pendiente">
                        <i class="fas fa-clock mr-1"></i>Pendientes
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-filter="almacenado">
                        <i class="fas fa-warehouse mr-1"></i>Almacenados
                    </button>
                    <button type="button" class="btn btn-outline-success" data-filter="entregado">
                        <i class="fas fa-check mr-1"></i>Entregados
                    </button>
                </div>
                <div class="input-group input-group-sm" style="max-width: 280px;">
                    <input type="text" id="buscarPedido" class="form-control" placeholder="Buscar por N° o cliente...">
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
                <i class="fas fa-table mr-2"></i>Listado de Pedidos
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
                    <i class="fas fa-cloud-download-alt mr-1"></i>Obteniendo pedidos del sistema...
                </p>
            </div>

            {{-- Error --}}
            <div id="error" class="p-5 text-center" style="display:none;">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h5 class="text-danger">Error al cargar datos</h5>
                <p id="errorMessage" class="text-muted mb-3"></p>
                <button class="btn btn-outline-danger btn-sm" onclick="cargarDatos()">
                    <i class="fas fa-redo mr-1"></i>Reintentar
                </button>
            </div>

            {{-- Empty --}}
            <div id="empty" class="p-5 text-center" style="display:none;">
                <div class="text-muted mb-3">
                    <i class="fas fa-inbox fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin pedidos</h5>
                <p class="text-muted mb-0">No hay pedidos disponibles para mostrar</p>
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
                                <i class="fas fa-file-alt text-muted mr-1"></i>N° Pedido
                            </th>
                            <th>
                                <i class="fas fa-user text-muted mr-1"></i>Cliente
                            </th>
                            <th>
                                <i class="fas fa-calendar text-muted mr-1"></i>Fecha Entrega
                            </th>
                            <th class="text-center">
                                <i class="fas fa-map-marker-alt text-muted mr-1"></i>Destinos
                            </th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyDatos">
                        {{-- filas dinámicas --}}
                    </tbody>
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
        .cliente-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .badge-estado {
            font-size: 0.8rem;
            padding: 0.5em 1em;
        }
        .destinos-badge {
            background: #f4f6f9;
            color: #495057;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
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
        .numero-pedido {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #17a2b8;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        let pedidosData = [];
        let filtroActual = 'todos';

        const estadoConfig = {
            'pendiente': { color: 'warning', icon: 'clock', text: 'Pendiente' },
            'almacenado': { color: 'info', icon: 'warehouse', text: 'Almacenado' },
            'entregado': { color: 'success', icon: 'check-double', text: 'Entregado' },
            'cancelado': { color: 'danger', icon: 'times-circle', text: 'Cancelado' },
            'default': { color: 'secondary', icon: 'question-circle', text: 'Desconocido' }
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
                console.log('🔄 GET /api/trazabilidad/pedidos/completo');

                const response = await fetch('/api/trazabilidad/pedidos/completo', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const json = await response.json();
                pedidosData = json?.data ?? [];

                loading.style.display = 'none';

                if (pedidosData.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Actualizar KPIs
                const pendientes = pedidosData.filter(p => p.pedido?.estado === 'pendiente').length;
                const almacenados = pedidosData.filter(p => p.pedido?.estado === 'almacenado').length;
                const entregados = pedidosData.filter(p => p.pedido?.estado === 'entregado').length;
                
                document.getElementById('totalPedidos').textContent = pedidosData.length;
                document.getElementById('pedidosPendientes').textContent = pendientes;
                document.getElementById('pedidosAlmacenado').textContent = almacenados;
                document.getElementById('pedidosEntregado').textContent = entregados;
                
                kpiCards.style.display = 'flex';
                kpiCards.classList.add('animate-in');
                
                filtrosCard.style.display = 'block';
                filtrosCard.classList.add('animate-in');

                renderizarTabla(pedidosData);

            } catch (e) {
                console.error('❌ Error:', e);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo obtener la información desde la API';
            }
        }

        function renderizarTabla(pedidos) {
            const tbody = document.getElementById('tbodyDatos');
            const tabla = document.getElementById('tablaDatos');
            tbody.innerHTML = '';

            pedidos.forEach((item, index) => {
                const pedido = item.pedido;
                const cliente = item.cliente;
                const destinos = item.destinos ?? [];

                const tr = document.createElement('tr');
                tr.className = 'animate-in';
                tr.style.animationDelay = `${index * 0.05}s`;
                tr.setAttribute('data-estado', pedido?.estado || 'default');

                const estado = estadoConfig[pedido?.estado] || estadoConfig['default'];
                const inicial = cliente?.razon_social?.charAt(0)?.toUpperCase() || '?';

                tr.innerHTML = `
                    <td class="align-middle">
                        <span class="text-muted font-weight-bold">#${pedido.pedido_id}</span>
                    </td>
                    <td class="align-middle">
                        <span class="numero-pedido">${pedido.numero_pedido}</span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="cliente-avatar mr-2">
                                ${inicial}
                            </div>
                            <div>
                                <strong>${cliente?.razon_social ?? '-'}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-envelope mr-1"></i>${cliente?.email ?? '-'}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <i class="fas fa-calendar-alt text-muted mr-1"></i>
                        ${pedido.fecha_entrega ?? '-'}
                    </td>
                    <td class="align-middle text-center">
                        <span class="destinos-badge">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                            ${destinos.length}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge badge-pill badge-${estado.color} badge-estado">
                            <i class="fas fa-${estado.icon} mr-1"></i>
                            ${estado.text}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <a href="/Trazabilidad/pedidos/${pedido.pedido_id}" 
                           class="btn btn-sm btn-primary" 
                           title="Ver detalle">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            tabla.style.display = 'table';
        }

        function filtrarPedidos(filtro) {
            filtroActual = filtro;
            let pedidosFiltrados = pedidosData;

            if (filtro !== 'todos') {
                pedidosFiltrados = pedidosData.filter(p => p.pedido?.estado === filtro);
            }

            // Aplicar búsqueda
            const busqueda = document.getElementById('buscarPedido').value.toLowerCase();
            if (busqueda) {
                pedidosFiltrados = pedidosFiltrados.filter(p => 
                    p.pedido?.numero_pedido?.toLowerCase().includes(busqueda) ||
                    p.cliente?.razon_social?.toLowerCase().includes(busqueda)
                );
            }

            if (pedidosFiltrados.length === 0) {
                document.getElementById('tablaDatos').style.display = 'none';
                document.getElementById('empty').style.display = 'block';
            } else {
                document.getElementById('empty').style.display = 'none';
                renderizarTabla(pedidosFiltrados);
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
                    filtrarPedidos(this.dataset.filter);
                });
            });

            // Búsqueda
            document.getElementById('buscarPedido')?.addEventListener('input', function() {
                filtrarPedidos(filtroActual);
            });
        });
    </script>
@endsection