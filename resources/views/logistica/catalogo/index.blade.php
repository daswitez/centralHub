@extends('layouts.app')

@section('page_title', 'Catálogos - Logística')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-book text-primary mr-2"></i>Catálogos de Logística
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-database mr-1"></i>OrgTrack - Gestión de catálogos base
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Logística</a></li>
                <li class="breadcrumb-item active">Catálogos</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- Cards de navegación visual --}}
    <div class="row mb-4" id="catalogoCards">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-catalogo" data-endpoint="catalogo-categorias">
                <div class="card-body text-center py-4">
                    <div class="icon-wrapper bg-gradient-primary mb-3">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h5 class="mb-1">Categorías</h5>
                    <p class="text-muted small mb-0">Clasificación de productos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-catalogo" data-endpoint="catalogo-productos">
                <div class="card-body text-center py-4">
                    <div class="icon-wrapper bg-gradient-success mb-3">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h5 class="mb-1">Productos</h5>
                    <p class="text-muted small mb-0">Catálogo de productos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-catalogo" data-endpoint="catalogo-tipos-empaque">
                <div class="card-body text-center py-4">
                    <div class="icon-wrapper bg-gradient-warning mb-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <h5 class="mb-1">Tipos de Empaque</h5>
                    <p class="text-muted small mb-0">Formatos de empaque</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-catalogo" data-endpoint="catalogo-tamano-conteo">
                <div class="card-body text-center py-4">
                    <div class="icon-wrapper bg-gradient-info mb-3">
                        <i class="fas fa-ruler"></i>
                    </div>
                    <h5 class="mb-1">Tamaño / Conteo</h5>
                    <p class="text-muted small mb-0">Dimensiones y conteos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Card de datos --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title" id="tituloTabla">
                <i class="fas fa-table mr-2"></i>Selecciona un catálogo
            </h3>
            <div class="card-tools">
                {{-- Botones de exportación --}}
                <div class="btn-group mr-2" id="exportBtns" style="display: none;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="exportarPDF()" title="Exportar PDF">
                        <i class="fas fa-file-pdf mr-1"></i>PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-success" onclick="exportarExcel()" title="Exportar Excel">
                        <i class="fas fa-file-excel mr-1"></i>Excel
                    </button>
                </div>
                <button id="btnRecargar" class="btn btn-sm btn-primary" style="display: none;">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Mensaje inicial --}}
            <div id="inicial" class="p-5 text-center">
                <div class="text-muted mb-3">
                    <i class="fas fa-hand-point-up fa-4x"></i>
                </div>
                <h5 class="text-muted">Selecciona un catálogo</h5>
                <p class="text-muted mb-0">Haz clic en una de las tarjetas para ver sus datos</p>
            </div>

            {{-- Loading --}}
            <div id="loading" class="p-5 text-center" style="display: none;">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-3 text-muted mb-0">
                    <i class="fas fa-cloud-download-alt mr-1"></i>Consultando catálogo...
                </p>
            </div>

            {{-- Error --}}
            <div id="error" class="p-5 text-center" style="display:none;">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h5 class="text-danger">Error de conexión</h5>
                <p id="errorMessage" class="text-muted mb-3"></p>
                <button class="btn btn-outline-danger btn-sm" onclick="cargarDatos(currentEndpoint)">
                    <i class="fas fa-redo mr-1"></i>Reintentar
                </button>
            </div>

            {{-- Empty --}}
            <div id="empty" class="p-5 text-center" style="display:none;">
                <div class="text-muted mb-3">
                    <i class="fas fa-inbox fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin datos</h5>
                <p class="text-muted mb-0">No hay registros en este catálogo</p>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="tablaDatos" class="table table-hover table-striped mb-0" style="display:none;">
                    <thead class="thead-dark" id="theadDatos"></thead>
                    <tbody id="tbodyDatos"></tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Estilos --}}
    <style>
        .card-catalogo {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .card-catalogo:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .card-catalogo.active {
            border-color: #007bff;
            box-shadow: 0 5px 15px rgba(0,123,255,0.3);
        }
        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }
        .badge-activo {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        .badge-inactivo {
            background: #6c757d;
        }
        @media print {
            .card-catalogo, .card-tools, .navbar, .sidebar, .breadcrumb {
                display: none !important;
            }
            .card {
                border: none !important;
            }
        }
    </style>

    {{-- JavaScript --}}
    <script>
        let currentEndpoint = null;
        let currentData = [];
        let currentConfig = null;

        const catalogoConfig = {
            'catalogo-categorias': {
                titulo: 'Categorías',
                icon: 'folder-open',
                columns: ['ID', 'Nombre', 'Descripción'],
                dataColumns: ['id', 'nombre', 'descripcion'],
                mapper: c => [
                    `<strong>#${c.id}</strong>`,
                    `<span class="font-weight-bold">${c.nombre || '—'}</span>`,
                    c.descripcion || '—'
                ],
                rawMapper: c => [c.id, c.nombre || '', c.descripcion || '']
            },
            'catalogo-productos': {
                titulo: 'Productos',
                icon: 'boxes',
                columns: ['ID', 'Nombre', 'Descripción', 'Peso Prom.', 'Categoría'],
                dataColumns: ['id', 'nombre', 'descripcion', 'peso_promedio', 'categoria'],
                mapper: p => [
                    `<strong>#${p.id}</strong>`,
                    `<span class="font-weight-bold">${p.nombre || '—'}</span>`,
                    p.descripcion || '—',
                    `<span class="badge badge-info">${p.peso_promedio || 0} kg</span>`,
                    `<span class="badge badge-primary">${p.categoria?.nombre || '—'}</span>`
                ],
                rawMapper: p => [p.id, p.nombre || '', p.descripcion || '', p.peso_promedio || 0, p.categoria?.nombre || '']
            },
            'catalogo-tipos-empaque': {
                titulo: 'Tipos de Empaque',
                icon: 'box',
                columns: ['ID', 'Nombre', 'Largo', 'Ancho', 'Alto', 'Tara', 'Capacidad', 'Uds/Pallet'],
                dataColumns: ['id', 'nombre', 'largo', 'ancho', 'alto', 'tara', 'capacidad', 'unidades_por_pallet'],
                mapper: e => [
                    `<strong>#${e.id}</strong>`,
                    `<span class="font-weight-bold">${e.nombre || '—'}</span>`,
                    `${e.largo || 0} cm`,
                    `${e.ancho || 0} cm`,
                    `${e.alto || 0} cm`,
                    `${e.tara || 0} kg`,
                    `${e.capacidad || 0} kg`,
                    `<span class="badge badge-secondary">${e.unidades_por_pallet || 0}</span>`
                ],
                rawMapper: e => [e.id, e.nombre || '', e.largo || 0, e.ancho || 0, e.alto || 0, e.tara || 0, e.capacidad || 0, e.unidades_por_pallet || 0]
            },
            'catalogo-tamano-conteo': {
                titulo: 'Tamaño / Conteo',
                icon: 'ruler',
                columns: ['ID', 'Nombre', 'Conteo/Empaque', 'Peso Prom.', 'Estado', 'Producto'],
                dataColumns: ['id', 'nombre', 'conteo_por_empaque', 'peso_promedio_unidad', 'activo', 'producto'],
                mapper: c => [
                    `<strong>#${c.id}</strong>`,
                    `<span class="font-weight-bold">${c.nombre || '—'}</span>`,
                    `<span class="badge badge-info">${c.conteo_por_empaque || 0}</span>`,
                    `${c.peso_promedio_unidad || 0} kg`,
                    c.activo 
                        ? '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Activo</span>'
                        : '<span class="badge badge-secondary">Inactivo</span>',
                    `<span class="badge badge-primary">${c.producto?.nombre || '—'}</span>`
                ],
                rawMapper: c => [c.id, c.nombre || '', c.conteo_por_empaque || 0, c.peso_promedio_unidad || 0, c.activo ? 'Activo' : 'Inactivo', c.producto?.nombre || '']
            }
        };

        function renderHeader(columns) {
            const thead = document.getElementById('theadDatos');
            thead.innerHTML = `
                <tr>
                    ${columns.map(c => `<th>${c}</th>`).join('')}
                </tr>
            `;
        }

        function renderRows(rows, mapper) {
            const tbody = document.getElementById('tbodyDatos');
            tbody.innerHTML = '';

            rows.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.className = 'animate-in';
                tr.style.animationDelay = `${index * 0.03}s`;
                tr.innerHTML = mapper(row).map(v => `<td class="align-middle">${v ?? '—'}</td>`).join('');
                tbody.appendChild(tr);
            });
        }

        async function cargarDatos(endpoint) {
            currentEndpoint = endpoint;
            currentConfig = catalogoConfig[endpoint];

            // Actualizar UI de cards
            document.querySelectorAll('.card-catalogo').forEach(card => {
                card.classList.remove('active');
            });
            document.querySelector(`[data-endpoint="${endpoint}"]`)?.classList.add('active');

            // Actualizar título
            document.getElementById('tituloTabla').innerHTML = 
                `<i class="fas fa-${currentConfig.icon} mr-2"></i>${currentConfig.titulo}`;

            const inicial = document.getElementById('inicial');
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tabla = document.getElementById('tablaDatos');
            const btnRecargar = document.getElementById('btnRecargar');
            const exportBtns = document.getElementById('exportBtns');

            // Reset UI
            inicial.style.display = 'none';
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tabla.style.display = 'none';
            btnRecargar.style.display = 'inline-block';
            exportBtns.style.display = 'none';

            try {
                const response = await fetch(`/api/orgtrack/${endpoint}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error();

                const json = await response.json();
                currentData = json.data ?? [];

                loading.style.display = 'none';

                if (!Array.isArray(currentData) || currentData.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                renderHeader(currentConfig.columns);
                renderRows(currentData, currentConfig.mapper);

                tabla.style.display = 'table';
                exportBtns.style.display = 'inline-flex';

            } catch (e) {
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo conectar con el servicio';
            }
        }

        // Exportar a PDF usando html2pdf.js
        function exportarPDF() {
            if (!currentData.length || !currentConfig) return;

            const fecha = new Date().toLocaleDateString('es-ES');
            const hora = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });

            // Crear contenedor temporal
            const container = document.createElement('div');
            container.innerHTML = `
                <div style="font-family: 'Segoe UI', Tahoma, sans-serif; padding: 20px; color: #333;">
                    <div style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                        <h1 style="font-size: 22px; margin: 0 0 5px 0;">Catálogo: ${currentConfig.titulo}</h1>
                        <p style="font-size: 12px; margin: 0; opacity: 0.9;">Logística - OrgTrack</p>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px;">
                        <span><strong>Fecha:</strong> ${fecha} ${hora}</span>
                        <span><strong>Total registros:</strong> ${currentData.length}</span>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr>
                                ${currentConfig.columns.map(c => `<th style="background: #343a40; color: white; padding: 10px 6px; text-align: left; font-weight: 600;">${c}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody>
                            ${currentData.map((row, i) => `
                                <tr style="background: ${i % 2 === 0 ? '#fff' : '#f8f9fa'};">
                                    ${currentConfig.rawMapper(row).map(v => `<td style="padding: 8px 6px; border-bottom: 1px solid #dee2e6;">${v}</td>`).join('')}
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <div style="margin-top: 20px; text-align: center; font-size: 10px; color: #6c757d; padding-top: 15px; border-top: 1px solid #dee2e6;">
                        Documento generado automáticamente - CentralHub © ${new Date().getFullYear()}
                    </div>
                </div>
            `;

            // Opciones de html2pdf
            const opt = {
                margin: 10,
                filename: `catalogo_${currentConfig.titulo.toLowerCase().replace(/\s+/g, '_')}_${new Date().toISOString().slice(0,10)}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Usar html2pdf
            if (typeof html2pdf !== 'undefined') {
                html2pdf().set(opt).from(container).save();
            } else {
                // Cargar librería si no está disponible
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                script.onload = () => {
                    html2pdf().set(opt).from(container).save();
                };
                document.head.appendChild(script);
            }
        }

        // Exportar a Excel (HTML con estilo)
        function exportarExcel() {
            if (!currentData.length || !currentConfig) return;

            const fecha = new Date().toLocaleDateString('es-ES');
            const hora = new Date().toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });

            const htmlContent = `
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        table { border-collapse: collapse; }
                        .header { 
                            background: #007bff; 
                            color: white; 
                            font-size: 18pt; 
                            font-weight: bold;
                            padding: 10px;
                        }
                        .subheader {
                            background: #f8f9fa;
                            font-size: 10pt;
                            color: #6c757d;
                        }
                        th { 
                            background: #343a40; 
                            color: white; 
                            font-weight: bold;
                            padding: 8px;
                            border: 1px solid #dee2e6;
                            text-align: left;
                        }
                        td { 
                            padding: 6px; 
                            border: 1px solid #dee2e6;
                        }
                        .even { background: #f8f9fa; }
                        .footer { 
                            font-size: 9pt; 
                            color: #6c757d;
                            font-style: italic;
                        }
                    </style>
                </head>
                <body>
                    <table>
                        <tr>
                            <td colspan="${currentConfig.columns.length}" class="header">
                                Catálogo: ${currentConfig.titulo}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="${currentConfig.columns.length}" class="subheader">
                                Logística - OrgTrack | Generado: ${fecha} ${hora} | Total: ${currentData.length} registros
                            </td>
                        </tr>
                        <tr>
                            ${currentConfig.columns.map(c => `<th>${c}</th>`).join('')}
                        </tr>
                        ${currentData.map((row, i) => `
                            <tr class="${i % 2 === 0 ? 'even' : ''}">
                                ${currentConfig.rawMapper(row).map(v => `<td>${v}</td>`).join('')}
                            </tr>
                        `).join('')}
                        <tr>
                            <td colspan="${currentConfig.columns.length}" class="footer">
                                Documento generado automáticamente - CentralHub © ${new Date().getFullYear()}
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
            `;

            const blob = new Blob([htmlContent], { type: 'application/vnd.ms-excel' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `catalogo_${currentConfig.titulo.toLowerCase().replace(/\s+/g, '_')}_${new Date().toISOString().slice(0,10)}.xls`;
            link.click();
            URL.revokeObjectURL(link.href);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Click en cards
            document.querySelectorAll('.card-catalogo').forEach(card => {
                card.addEventListener('click', function() {
                    cargarDatos(this.dataset.endpoint);
                });
            });

            // Botón recargar
            document.getElementById('btnRecargar')?.addEventListener('click', () => {
                if (currentEndpoint) cargarDatos(currentEndpoint);
            });
        });
    </script>
@endsection