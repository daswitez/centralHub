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
            </nav>

            {{-- Sidebar izquierdo --}}
            <aside class="main-sidebar sidebar-dark-primary elevation-4" aria-label="Barra lateral">
                {{-- Logo/brand --}}
                <a href="{{ url('/') }}" class="brand-link text-decoration-none">
                    <span class="brand-text font-weight-light">AdminLTE 3</span>
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
                            <li class="nav-item">
                                <a href="{{ route('panel.logistica') }}" class="nav-link">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Logística</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('panel.planta') }}" class="nav-link">
                                    <i class="nav-icon fas fa-industry"></i>
                                    <p>Planta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('panel.certificaciones') }}" class="nav-link">
                                    <i class="nav-icon fas fa-certificate"></i>
                                    <p>Certificaciones</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-database"></i>
                                    <p>Catálogos <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.departamentos.index') }}"><p>Departamentos</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.municipios.index') }}"><p>Municipios</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.variedades.index') }}"><p>Variedades</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.plantas.index') }}"><p>Plantas</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.clientes.index') }}"><p>Clientes</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.transportistas.index') }}"><p>Transportistas</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('cat.almacenes.index') }}"><p>Almacenes</p></a></li>
                                </ul>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tractor"></i>
                                    <p>Campo <i class="right fas fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item"><a class="nav-link" href="{{ route('campo.productores.index') }}"><p>Productores</p></a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('campo.lotes.index') }}"><p>Lotes de campo</p></a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            {{-- Área principal de contenido --}}
            <div class="content-wrapper">
                {{-- Cabecera de página opcional --}}
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
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


