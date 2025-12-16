@extends('layouts.app')

@section('page_title', 'Productos - Catálogo')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-boxes text-primary mr-2"></i>Catálogo de Productos
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-barcode mr-1"></i>Gestión y trazabilidad de productos
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Trazabilidad</a></li>
                <li class="breadcrumb-item active">Productos</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- KPI Cards --}}
    <div class="row" id="kpiCards" style="display: none;">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-primary">
                <div class="inner">
                    <h3 id="totalProductos">0</h3>
                    <p>Total Productos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3 id="productosActivos">0</h3>
                    <p>Productos Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-gradient-secondary">
                <div class="inner">
                    <h3 id="productosInactivos">0</h3>
                    <p>Inactivos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros rápidos --}}
    <div class="card card-outline card-primary mb-3" id="filtrosCard" style="display: none;">
        <div class="card-body py-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" data-filter="todos">
                        <i class="fas fa-list mr-1"></i>Todos
                    </button>
                    <button type="button" class="btn btn-outline-success" data-filter="activo">
                        <i class="fas fa-check mr-1"></i>Activos
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-filter="inactivo">
                        <i class="fas fa-pause mr-1"></i>Inactivos
                    </button>
                </div>
                <div class="input-group input-group-sm" style="max-width: 250px;">
                    <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar producto...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla Principal --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table mr-2"></i>Listado de Productos
            </h3>
            <div class="card-tools">
                <button id="btnRecargar" class="btn btn-sm btn-primary">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Loading con animación mejorada --}}
            <div id="loading" class="p-5 text-center">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-3 text-muted mb-0">
                    <i class="fas fa-cloud-download-alt mr-1"></i>Obteniendo productos del sistema...
                </p>
            </div>

            {{-- Error mejorado --}}
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

            {{-- Empty state mejorado --}}
            <div id="empty" class="p-5 text-center" style="display:none;">
                <div class="text-muted mb-3">
                    <i class="fas fa-inbox fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin productos</h5>
                <p class="text-muted mb-0">No hay productos disponibles en el catálogo</p>
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
                                <i class="fas fa-barcode text-muted mr-1"></i>Código
                            </th>
                            <th>
                                <i class="fas fa-box text-muted mr-1"></i>Producto
                            </th>
                            <th>
                                <i class="fas fa-tag text-muted mr-1"></i>Tipo
                            </th>
                            <th class="text-center">
                                <i class="fas fa-weight text-muted mr-1"></i>Peso
                            </th>
                            <th class="text-right">
                                <i class="fas fa-dollar-sign text-muted mr-1"></i>Precio
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

    {{-- Estilos adicionales --}}
    <style>
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
            transform: translateX(3px);
        }
        .badge-producto {
            font-size: 0.85rem;
            padding: 0.4em 0.8em;
        }
        .btn-action {
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
        .producto-codigo {
            font-family: 'Courier New', monospace;
            background: #f4f6f9;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        .small-box {
            transition: all 0.3s ease;
        }
        .small-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .spinner-border {
            animation: spinner-border .75s linear infinite;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        let productosData = [];
        let filtroActual = 'todos';

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
                console.log('🔄 GET /api/trazabilidad/products');

                const response = await fetch('/api/trazabilidad/products', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const json = await response.json();
                productosData = json?.data?.data ?? [];

                loading.style.display = 'none';

                if (productosData.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Actualizar KPIs
                const activos = productosData.filter(p => p.activo).length;
                const inactivos = productosData.length - activos;
                
                document.getElementById('totalProductos').textContent = productosData.length;
                document.getElementById('productosActivos').textContent = activos;
                document.getElementById('productosInactivos').textContent = inactivos;
                
                kpiCards.style.display = 'flex';
                kpiCards.classList.add('animate-in');
                
                filtrosCard.style.display = 'block';
                filtrosCard.classList.add('animate-in');

                renderizarTabla(productosData);

            } catch (e) {
                console.error('❌ Error:', e);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo obtener la información desde la API';
            }
        }

        function renderizarTabla(productos) {
            const tbody = document.getElementById('tbodyDatos');
            const tabla = document.getElementById('tablaDatos');
            tbody.innerHTML = '';

            productos.forEach((producto, index) => {
                const tr = document.createElement('tr');
                tr.className = 'animate-in';
                tr.style.animationDelay = `${index * 0.05}s`;
                tr.setAttribute('data-activo', producto.activo ? 'activo' : 'inactivo');

                const tipoColores = {
                    'procesado': 'info',
                    'fresco': 'success',
                    'congelado': 'primary',
                    'default': 'secondary'
                };
                const tipoColor = tipoColores[producto.tipo?.toLowerCase()] || tipoColores['default'];

                tr.innerHTML = `
                    <td class="align-middle">
                        <span class="text-muted font-weight-bold">#${producto.producto_id}</span>
                    </td>
                    <td class="align-middle">
                        <span class="producto-codigo">${producto.codigo}</span>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <i class="fas fa-box-open text-primary"></i>
                            </div>
                            <div>
                                <strong>${producto.nombre}</strong>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <span class="badge badge-${tipoColor} badge-producto">
                            ${producto.tipo}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="font-weight-bold">${producto.peso}</span>
                        <small class="text-muted">${producto.unit?.codigo ?? ''}</small>
                    </td>
                    <td class="align-middle text-right">
                        <span class="text-success font-weight-bold">Bs. ${producto.precio_unitario}</span>
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge badge-pill badge-${producto.activo ? 'success' : 'secondary'} px-3 py-2">
                            <i class="fas fa-${producto.activo ? 'check-circle' : 'pause-circle'} mr-1"></i>
                            ${producto.activo ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        <a href="/Trazabilidad/productos/${producto.producto_id}" 
                           class="btn btn-sm btn-primary btn-action" 
                           title="Ver detalle">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                `;

                tbody.appendChild(tr);
            });

            tabla.style.display = 'table';
        }

        function filtrarProductos(filtro) {
            filtroActual = filtro;
            let productosFiltrados = productosData;

            if (filtro === 'activo') {
                productosFiltrados = productosData.filter(p => p.activo);
            } else if (filtro === 'inactivo') {
                productosFiltrados = productosData.filter(p => !p.activo);
            }

            // Aplicar búsqueda también
            const busqueda = document.getElementById('buscarProducto').value.toLowerCase();
            if (busqueda) {
                productosFiltrados = productosFiltrados.filter(p => 
                    p.nombre.toLowerCase().includes(busqueda) ||
                    p.codigo.toLowerCase().includes(busqueda)
                );
            }

            if (productosFiltrados.length === 0) {
                document.getElementById('tablaDatos').style.display = 'none';
                document.getElementById('empty').style.display = 'block';
            } else {
                document.getElementById('empty').style.display = 'none';
                renderizarTabla(productosFiltrados);
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
                    filtrarProductos(this.dataset.filter);
                });
            });

            // Búsqueda
            document.getElementById('buscarProducto')?.addEventListener('input', function() {
                filtrarProductos(filtroActual);
            });
        });
    </script>
@endsection