@extends('layouts.app')

@section('page_title', 'Detalle del Producto')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-box-open text-primary mr-2"></i>Detalle del Producto
            </h1>
            <p class="text-muted mb-0 mt-1" id="subtitulo">
                <i class="fas fa-barcode mr-1"></i>Cargando información...
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="/Trazabilidad/productos">Productos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- Loading mejorado --}}
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Cargando...</span>
        </div>
        <p class="mt-3 text-muted mb-0">
            <i class="fas fa-cloud-download-alt mr-1"></i>Obteniendo información del producto...
        </p>
    </div>

    {{-- Error mejorado --}}
    <div id="error" class="text-center py-5" style="display:none;">
        <div class="text-danger mb-3">
            <i class="fas fa-exclamation-circle fa-4x"></i>
        </div>
        <h5 class="text-danger">Error al cargar producto</h5>
        <p id="errorMessage" class="text-muted mb-3"></p>
        <a href="/Trazabilidad/productos" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left mr-1"></i>Volver al listado
        </a>
    </div>

    {{-- Contenido Principal --}}
    <div id="contenido" style="display:none;">
        <div class="row">
            {{-- Columna Principal --}}
            <div class="col-lg-8">
                {{-- Card de información principal --}}
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>Información del Producto
                        </h3>
                        <div class="card-tools">
                            <span id="estadoBadge" class="badge badge-pill px-3 py-2"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light mb-3">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Código</span>
                                        <span class="info-box-number" id="codigo" style="font-family: monospace;"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light mb-3">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text text-muted">Tipo</span>
                                        <span class="info-box-number" id="tipo"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="callout callout-info">
                            <h5 id="nombre" class="mb-1"></h5>
                            <p id="descripcion" class="mb-0 text-muted"></p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-gradient-light text-center">
                                    <div class="card-body py-3">
                                        <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                                        <h4 class="mb-0" id="peso"></h4>
                                        <small class="text-muted">Peso</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-light text-center">
                                    <div class="card-body py-3">
                                        <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                        <h4 class="mb-0 text-success" id="precio"></h4>
                                        <small class="text-muted">Precio Unitario</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-light text-center">
                                    <div class="card-body py-3">
                                        <i class="fas fa-ruler fa-2x text-info mb-2"></i>
                                        <h4 class="mb-0" id="unidad"></h4>
                                        <small class="text-muted">Unidad</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Card de estado --}}
                <div class="card" id="cardEstado">
                    <div class="card-body text-center py-4">
                        <div id="iconoEstado" class="mb-3">
                            <i class="fas fa-3x"></i>
                        </div>
                        <h5 id="textoEstado" class="mb-0"></h5>
                        <small class="text-muted">Estado del producto</small>
                    </div>
                </div>

                {{-- Acciones rápidas --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt mr-2"></i>Acciones
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="/Trazabilidad/productos" class="list-group-item list-group-item-action">
                                <i class="fas fa-arrow-left mr-2 text-muted"></i>
                                Volver al listado
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" onclick="window.print(); return false;">
                                <i class="fas fa-print mr-2 text-muted"></i>
                                Imprimir ficha
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-info-circle mr-1"></i>Información
                        </h6>
                        <p class="small text-muted mb-0">
                            Este producto forma parte del catálogo de trazabilidad. 
                            Todos los movimientos y transacciones quedan registrados 
                            para su posterior consulta.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Estilos adicionales --}}
    <style>
        .info-box {
            transition: all 0.3s ease;
        }
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card {
            transition: all 0.3s ease;
        }
        #cardEstado {
            border-left: 4px solid #28a745;
        }
        #cardEstado.inactivo {
            border-left-color: #6c757d;
        }
        .callout {
            border-radius: 0.25rem;
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
        async function cargarProducto() {
            try {
                const response = await fetch(
                    `/api/trazabilidad/products/{{ $productoId }}`,
                    { headers: { 'Accept': 'application/json' } }
                );

                if (!response.ok) {
                    throw new Error('Error al cargar producto');
                }

                const json = await response.json();
                const p = json?.data ?? null;

                if (!p) throw new Error('Producto no encontrado');

                // Actualizar subtítulo
                document.getElementById('subtitulo').innerHTML = 
                    `<i class="fas fa-barcode mr-1"></i>Producto: ${p.codigo}`;

                // Información principal
                document.getElementById('codigo').innerText = p.codigo;
                document.getElementById('nombre').innerText = p.nombre;
                document.getElementById('tipo').innerText = p.tipo;
                document.getElementById('peso').innerText = `${p.peso} ${p.unit?.codigo ?? ''}`;
                document.getElementById('precio').innerText = `Bs. ${p.precio_unitario}`;
                document.getElementById('unidad').innerText = p.unit?.nombre ?? '-';
                document.getElementById('descripcion').innerText = p.descripcion ?? 'Sin descripción disponible';

                // Estado
                const estadoBadge = document.getElementById('estadoBadge');
                const iconoEstado = document.getElementById('iconoEstado');
                const textoEstado = document.getElementById('textoEstado');
                const cardEstado = document.getElementById('cardEstado');

                if (p.activo) {
                    estadoBadge.className = 'badge badge-pill badge-success px-3 py-2';
                    estadoBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Activo';
                    iconoEstado.innerHTML = '<i class="fas fa-check-circle fa-3x text-success"></i>';
                    textoEstado.innerText = 'Producto Activo';
                    textoEstado.className = 'text-success mb-0';
                } else {
                    estadoBadge.className = 'badge badge-pill badge-secondary px-3 py-2';
                    estadoBadge.innerHTML = '<i class="fas fa-pause-circle mr-1"></i>Inactivo';
                    iconoEstado.innerHTML = '<i class="fas fa-pause-circle fa-3x text-secondary"></i>';
                    textoEstado.innerText = 'Producto Inactivo';
                    textoEstado.className = 'text-secondary mb-0';
                    cardEstado.classList.add('inactivo');
                }

                // Mostrar contenido
                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';
                document.getElementById('contenido').classList.add('animate-in');

            } catch (e) {
                console.error(e);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo cargar el producto';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarProducto);
    </script>
@endsection