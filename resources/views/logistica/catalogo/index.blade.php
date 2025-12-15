@extends('layouts.app')

@section('page_title', 'Catálogos - Logística')

@section('page_header')
    <div>
        <h1 class="m-0">Catálogos</h1>
        <p class="text-muted mb-0">
            Servicio de Logística - OrgTrack
        </p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">

        {{-- Tabs --}}
        <div class="card-header p-0">
            <ul class="nav nav-tabs" id="catalogoTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-endpoint="catalogo-categorias">
                        Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-endpoint="catalogo-productos">
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-endpoint="catalogo-tipos-empaque">
                        Tipos de Empaque
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-endpoint="catalogo-tamano-conteo">
                        Tamaño / Conteo
                    </a>
                </li>
            </ul>
        </div>

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listado</h3>
            <button id="btnRecargar" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt"></i> Recargar
            </button>
        </div>

        <div class="card-body p-0">

            {{-- Loading --}}
            <div id="loading" class="p-4 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Consultando datos...</p>
            </div>

            {{-- Error --}}
            <div id="error" class="p-4 text-center text-danger" style="display:none;">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <p id="errorMessage" class="mb-2"></p>
                <button class="btn btn-sm btn-outline-danger" onclick="cargarDatos()">
                    <i class="fas fa-redo"></i> Reintentar
                </button>
            </div>

            {{-- Empty --}}
            <div id="empty" class="p-4 text-center text-muted" style="display:none;">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>No hay datos disponibles</p>
            </div>

            {{-- Tabla --}}
            <table id="tablaDatos" class="table table-hover mb-0" style="display:none;">
                <thead></thead>
                <tbody id="tbodyDatos"></tbody>
            </table>

        </div>
    </div>

    <script>
        let currentEndpoint = 'catalogo-categorias';

        function renderHeader(columns) {
            const thead = document.querySelector('#tablaDatos thead');
            thead.innerHTML = `
                    <tr>
                        ${columns.map(c => `<th>${c}</th>`).join('')}
                    </tr>
                `;
        }

        function renderRows(rows, mapper) {
            const tbody = document.getElementById('tbodyDatos');
            tbody.innerHTML = '';

            rows.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                        ${mapper(row).map(v => `<td>${v ?? '—'}</td>`).join('')}
                    `;
                tbody.appendChild(tr);
            });
        }

        async function cargarDatos(endpoint = currentEndpoint) {
            currentEndpoint = endpoint;

            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tabla = document.getElementById('tablaDatos');
            const tbody = document.getElementById('tbodyDatos');

            // Reset UI
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tabla.style.display = 'none';
            tbody.innerHTML = '';

            try {
                const response = await fetch(`/api/orgtrack/${endpoint}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error();

                const json = await response.json();
                const data = json.data ?? [];

                loading.style.display = 'none';

                if (!Array.isArray(data) || data.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // CATEGORÍAS
                if (endpoint === 'catalogo-categorias') {
                    renderHeader(['ID', 'Nombre', 'Descripción']);
                    renderRows(data, c => [c.id, c.nombre, c.descripcion]);
                }

                // PRODUCTOS
                if (endpoint === 'catalogo-productos') {
                    renderHeader(['ID', 'Nombre', 'Descripción', 'Peso Promedio', 'Categoría']);
                    renderRows(data, p => [
                        p.id,
                        p.nombre,
                        p.descripcion,
                        p.peso_promedio,
                        p.categoria?.nombre
                    ]);
                }

                // EMPAQUE
                if (endpoint === 'catalogo-tipos-empaque') {
                    renderHeader([
                        'ID', 'Nombre', 'Descripción',
                        'Largo', 'Ancho', 'Alto',
                        'Tara', 'Capacidad', 'Unidades/Pallet'
                    ]);

                    renderRows(data, e => [
                        e.id,
                        e.nombre,
                        e.descripcion,
                        e.largo,
                        e.ancho,
                        e.alto,
                        e.tara,
                        e.capacidad,
                        e.unidades_por_pallet
                    ]);
                }

                // CONTEO
                if (endpoint === 'catalogo-tamano-conteo') {
                    renderHeader([
                        'ID', 'Nombre', 'Conteo por Empaque',
                        'Peso Promedio Unidad', 'Activo', 'Producto'
                    ]);

                    renderRows(data, c => [
                        c.id,
                        c.nombre,
                        c.conteo_por_empaque,
                        c.peso_promedio_unidad,
                        c.activo ? 'Sí' : 'No',
                        c.producto?.nombre
                    ]);
                }

                tabla.style.display = 'table';

            } catch (e) {
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo obtener la información desde la API';
            }
        }

        // Tabs
        document.querySelectorAll('#catalogoTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('#catalogoTabs .nav-link')
                    .forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                cargarDatos(tab.dataset.endpoint);
            });
        });

        document.addEventListener('DOMContentLoaded', () => cargarDatos());
        document.getElementById('btnRecargar')?.addEventListener('click', () => cargarDatos());
    </script>
@endsection