<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel + AdminLTE 3') }}</title>
    {{-- Carga de assets compilados por Vite (incluye Tailwind, AdminLTE CSS y JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    {{-- Contenedor principal de AdminLTE --}}
    <div class="wrapper">
        {{-- Navbar superior minimal --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" aria-label="Barra de navegación">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Abrir/Cerrar menú">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('/') }}" class="nav-link">Inicio</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user mr-1"></i> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        {{-- Sidebar izquierdo --}}
        <aside class="main-sidebar sidebar-dark-primary elevation-4" aria-label="Barra lateral">
            {{-- Logo/brand --}}
            <a href="{{ url('/') }}" class="brand-link text-decoration-none">
                <span class="brand-text font-weight-light">Central Hub</span>
            </a>
            {{-- Contenido del sidebar --}}
            <div class="sidebar">
                <nav class="mt-2" aria-label="Menú lateral">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('panel.home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('panel.ventas') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Ventas</p>
                            </a>
                        </li>
                        {{-- Logística panel removido del sidebar pero sigue accesible vía URL --}}
                        <li class="nav-item">
                            <a href="{{ route('panel.planta') }}" class="nav-link">
                                <i class="nav-icon fas fa-industry"></i>
                                <p>Planta</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('certificaciones.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-certificate"></i>
                                <p>Certificaciones</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Almacén <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{ route('panel.almacen') }}"><i
                                            class="fas fa-tachometer-alt mr-2"></i>
                                        <p>Dashboard</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.almacenes.index') }}"><i
                                            class="fas fa-cogs mr-2"></i>
                                        <p>Gestión Almacenes</p>
                                    </a></li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-database"></i>
                                <p>Catálogos <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.departamentos.index') }}">
                                        <p>Departamentos</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.municipios.index') }}">
                                        <p>Municipios</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.variedades.index') }}">
                                        <p>Variedades</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.plantas.index') }}">
                                        <p>Plantas</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.clientes.index') }}">
                                        <p>Clientes</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('cat.transportistas.index') }}">
                                        <p>Transportistas</p>
                                    </a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tractor"></i>
                                <p>Campo <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a class="nav-link" href="{{ route('campo.productores.index') }}">
                                        <p>Productores</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('campo.lotes.index') }}">
                                        <p>Lotes de campo</p>
                                    </a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('campo.lecturas.index') }}">
                                        <p>Lecturas sensores</p>
                                    </a></li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-exchange-alt"></i>
                                <p>Transacciones <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('tx.planta.lotes-planta.index') }}">
                                        <p>Ver Lotes de Planta</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('tx.planta.lotes-salida.index') }}">
                                        <p>Ver Lotes de Salida</p>
                                    </a>
                                </li>
                                {{-- Links a formularios de creación removidos - Los lotes se gestionan desde el
                                microservicio de Trazabilidad --}}
                            </ul>
                        </li>
                        <!-- <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>Comercial <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('comercial.pedidos.index') }}">
                                        <p>Pedidos / Ventas</p>
                                    </a>
                                </li>
                                {{-- Link a crear pedido removido - Los pedidos se crean desde el microservicio de
                                Trazabilidad --}}
                            </ul>
                        </li> -->

                        {{-- Menú de Solicitudes --}}
                        <!-- <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Solicitudes <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('solicitudes.index') }}">
                                        <p>Mis Solicitudes (Planta)</p>
                                    </a>
                                </li>
                                {{-- Link a crear solicitud removido - Las solicitudes se gestionan desde el
                                microservicio de OrgTrack --}}
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('solicitudes.mis-solicitudes') }}">
                                        <p>Solicitudes Recibidas (Productor)</p>
                                    </a>
                                </li>
                            </ul>
                        </li> -->

                        {{-- Estado de Lotes --}}
                        <li class="nav-item">
                            <a href="{{ route('extra.recurso1') }}" class="nav-link">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>Estado de Lotes</p>
                            </a>
                        </li>


                        {{-- Menú de Reportes Analíticos --}}
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Reportes <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reportes.index') }}">
                                        <i class="fas fa-list mr-2"></i>
                                        <p>Centro de Reportes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reportes.rentabilidad.index') }}">
                                        <i class="fas fa-chart-pie mr-2"></i>
                                        <p>Rentabilidad Clientes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reportes.rendimiento.index') }}">
                                        <i class="fas fa-industry mr-2"></i>
                                        <p>Rendimiento Plantas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reportes.logistica.index') }}">
                                        <i class="fas fa-truck mr-2"></i>
                                        <p>Análisis Logístico</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('reportes.inventario.index') }}">
                                        <i class="fas fa-boxes mr-2"></i>
                                        <p>Estado Inventario</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Trazabilidad sidebar--}}
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-project-diagram"></i>
                                <p>Trazabilidad <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Trazabilidad.productos.index') }}">
                                        <p>Productos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Trazabilidad.pedidos.index') }}">
                                        <p>Pedidos</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Logística sidebar--}}
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-truck"></i>
                                <p>Logística <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logistica.envios.index') }}">
                                        <p>Envios</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logistica.catalogo.index') }}">
                                        <p>Catálogo</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logistica.tipos-transporte.index') }}">
                                        <p>Tipos de Transporte</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logistica.envios-productores.index') }}">
                                        <p>Envios Productores</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                </nav>
            </div>
        </aside>

        {{-- Área principal de contenido --}}
        <div class="content-wrapper">
            {{-- Cabecera de página opcional: usa page_header si existe, si no sólo page_title simple --}}
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            @hasSection('page_header')
                                @yield('page_header')
                            @else
                                <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- Contenido inyectado por cada vista --}}
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        {{-- Footer --}}
        <footer class="main-footer text-sm">
            <strong>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</strong>
            <span class="ml-1">Todos los derechos reservados.</span>
        </footer>
    </div>
</body>

</html>