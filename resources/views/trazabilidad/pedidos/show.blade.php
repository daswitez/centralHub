@extends('layouts.app')

@section('page_title', 'Detalle del Pedido')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-file-invoice text-info mr-2"></i>Detalle del Pedido
            </h1>
            <p class="text-muted mb-0 mt-1" id="subtitulo">
                <i class="fas fa-clipboard-list mr-1"></i>Cargando información...
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="/Trazabilidad/pedidos">Pedidos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- Loading --}}
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Cargando...</span>
        </div>
        <p class="mt-3 text-muted mb-0">
            <i class="fas fa-cloud-download-alt mr-1"></i>Obteniendo información del pedido...
        </p>
    </div>

    {{-- Error --}}
    <div id="error" class="text-center py-5" style="display:none;">
        <div class="text-danger mb-3">
            <i class="fas fa-exclamation-circle fa-4x"></i>
        </div>
        <h5 class="text-danger">Error al cargar pedido</h5>
        <p id="errorMessage" class="text-muted mb-3"></p>
        <a href="/Trazabilidad/pedidos" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-1"></i>Volver al listado
        </a>
    </div>

    {{-- Contenido Principal --}}
    <div id="contenido" style="display:none;">
        
        {{-- Header con estado --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-gradient-light">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">
                                    Pedido <span id="numeroPedidoHeader" class="text-info" style="font-family: monospace;"></span>
                                </h4>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt mr-1"></i>Entrega: <span id="fechaEntregaHeader"></span>
                                </small>
                            </div>
                            <div id="estadoBadge" class="badge badge-pill px-4 py-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cards de resumen --}}
        <div class="row">
            <div class="col-lg-4 col-6">
                <div class="info-box bg-white shadow-sm">
                    <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Productos</span>
                        <span class="info-box-number" id="totalProductos">0</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="info-box bg-white shadow-sm">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Subtotal</span>
                        <span class="info-box-number" id="subtotal">Bs. 0</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="info-box bg-white shadow-sm">
                    <span class="info-box-icon bg-warning"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Destinos</span>
                        <span class="info-box-number" id="totalDestinos">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Card Cliente --}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-tie mr-2"></i>Información del Cliente
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="cliente-avatar mr-3" id="clienteAvatar">?</div>
                            <div>
                                <h5 class="mb-1" id="clienteNombre"></h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope mr-1"></i>
                                    <span id="clienteEmail"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Productos --}}
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shopping-cart mr-2"></i>Productos del Pedido
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th><i class="fas fa-box mr-1 text-muted"></i>Producto</th>
                                    <th class="text-center"><i class="fas fa-hashtag mr-1 text-muted"></i>Cantidad</th>
                                    <th class="text-right"><i class="fas fa-dollar-sign mr-1 text-muted"></i>Total</th>
                                </tr>
                            </thead>
                            <tbody id="productosTable"></tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-right font-weight-bold">Total:</td>
                                    <td class="text-right font-weight-bold text-success" id="totalPrecio">Bs. 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Card Destinos --}}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marked-alt mr-2"></i>Destinos de Entrega
                        </h3>
                    </div>
                    <div class="card-body" id="destinos">
                        {{-- Destinos dinámicos --}}
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Timeline de estado --}}
                <div class="card">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="fas fa-history mr-2"></i>Estado del Pedido
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-inverse" id="timeline">
                            <div class="time-label">
                                <span class="bg-info" id="fechaLabel">Fecha</span>
                            </div>
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
                            <a href="/Trazabilidad/pedidos" class="list-group-item list-group-item-action">
                                <i class="fas fa-arrow-left mr-2 text-muted"></i>
                                Volver al listado
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" onclick="window.print(); return false;">
                                <i class="fas fa-print mr-2 text-muted"></i>
                                Imprimir pedido
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estilos --}}
    <style>
        .cliente-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .destino-card {
            border-left: 4px solid #ffc107;
            background: #fffdf5;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .destino-card:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .destino-card:last-child {
            margin-bottom: 0;
        }
        .info-box {
            transition: all 0.3s ease;
        }
        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
        .timeline > div > .timeline-item {
            border-radius: 0.5rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeIn 0.4s ease forwards;
        }
        .producto-row {
            transition: all 0.2s ease;
        }
        .producto-row:hover {
            background-color: rgba(40, 167, 70, 0.05) !important;
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
            'pendiente': { color: 'warning', icon: 'clock', text: 'Pendiente' },
            'almacenado': { color: 'info', icon: 'warehouse', text: 'Almacenado' },
            'entregado': { color: 'success', icon: 'check-double', text: 'Entregado' },
            'cancelado': { color: 'danger', icon: 'times-circle', text: 'Cancelado' },
            'default': { color: 'secondary', icon: 'question-circle', text: 'Desconocido' }
        };

        async function cargarDetalle() {
            try {
                const response = await fetch(
                    `/api/trazabilidad/pedidos/{{ $pedidoId }}/completo`,
                    { headers: { 'Accept': 'application/json' } }
                );

                if (!response.ok) {
                    throw new Error('Error al cargar pedido');
                }

                const json = await response.json();
                const data = json?.data ?? null;

                if (!data) throw new Error('Pedido no encontrado');

                // Subtítulo
                document.getElementById('subtitulo').innerHTML = 
                    `<i class="fas fa-clipboard-list mr-1"></i>Pedido: ${data.pedido.numero_pedido}`;

                // Header
                document.getElementById('numeroPedidoHeader').textContent = data.pedido.numero_pedido;
                document.getElementById('fechaEntregaHeader').textContent = data.pedido.fecha_entrega || '-';
                document.getElementById('fechaLabel').textContent = data.pedido.fecha_entrega || 'Fecha';

                // Estado
                const estado = estadoConfig[data.pedido.estado] || estadoConfig['default'];
                const estadoBadge = document.getElementById('estadoBadge');
                estadoBadge.className = `badge badge-pill badge-${estado.color} px-4 py-2`;
                estadoBadge.innerHTML = `<i class="fas fa-${estado.icon} mr-1"></i> ${estado.text}`;

                // Resumen
                document.getElementById('totalProductos').textContent = data.resumen.total_productos || 0;
                document.getElementById('subtotal').textContent = `Bs. ${data.resumen.subtotal || 0}`;
                document.getElementById('totalDestinos').textContent = data.destinos?.length || 0;

                // Cliente
                const inicial = data.cliente?.razon_social?.charAt(0)?.toUpperCase() || '?';
                document.getElementById('clienteAvatar').textContent = inicial;
                document.getElementById('clienteNombre').textContent = data.cliente?.razon_social || '-';
                document.getElementById('clienteEmail').textContent = data.cliente?.email || '-';

                // Productos
                const productosTable = document.getElementById('productosTable');
                let totalPrecio = 0;
                data.productos.forEach(p => {
                    totalPrecio += parseFloat(p.precio_total) || 0;
                    productosTable.innerHTML += `
                        <tr class="producto-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <i class="fas fa-box text-success"></i>
                                    </div>
                                    <div>
                                        <strong>${p.producto?.nombre || '-'}</strong>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light px-3 py-2">${p.cantidad}</span>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">Bs. ${p.precio_total}</strong>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById('totalPrecio').textContent = `Bs. ${totalPrecio.toFixed(2)}`;

                // Destinos
                const destinosDiv = document.getElementById('destinos');
                if (data.destinos && data.destinos.length > 0) {
                    data.destinos.forEach((d, index) => {
                        destinosDiv.innerHTML += `
                            <div class="destino-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            Destino ${index + 1}
                                        </h6>
                                        <p class="mb-1">${d.direccion || '-'}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-user mr-1"></i>${d.nombre_contacto || '-'}
                                        </small>
                                    </div>
                                    <span class="badge badge-info">${d.total_productos || 0} productos</span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    destinosDiv.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                            <p class="mb-0">Sin destinos registrados</p>
                        </div>
                    `;
                }

                // Timeline
                const timeline = document.getElementById('timeline');
                timeline.innerHTML += `
                    <div>
                        <i class="fas fa-clipboard bg-info"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> Creación</span>
                            <h3 class="timeline-header">Pedido creado</h3>
                            <div class="timeline-body">
                                Pedido #${data.pedido.numero_pedido} registrado en el sistema.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-${estado.icon} bg-${estado.color}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> Actual</span>
                            <h3 class="timeline-header">Estado: ${estado.text}</h3>
                            <div class="timeline-body">
                                El pedido se encuentra actualmente en estado ${estado.text.toLowerCase()}.
                            </div>
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-clock bg-secondary"></i>
                    </div>
                `;

                // Mostrar contenido
                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';
                document.getElementById('contenido').classList.add('animate-in');

            } catch (e) {
                console.error(e);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo cargar el detalle del pedido';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDetalle);
    </script>
@endsection