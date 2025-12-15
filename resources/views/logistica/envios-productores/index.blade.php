@extends('layouts.app')

@section('page_title', 'Envios - Logística')

@section('page_header')
    <div>
        <h1 class="m-0">Envios</h1>
        <p class="text-muted mb-0">
            Servicio de Logística
        </p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
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

            {{-- Tabla (estructura solamente) --}}
            <table id="tablaDatos" class="table table-hover mb-0" style="display:none;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Remitente</th>
                        <th>Teléfono</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodyDatos">
                    {{-- filas --}}
                </tbody>
            </table>

        </div>
    </div>

    {{-- JS --}}
    <script>
        async function cargarDatos() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tabla = document.getElementById('tablaDatos');
            const tbody = document.getElementById('tbodyDatos');

            // reset UI
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tabla.style.display = 'none';
            tbody.innerHTML = '';

            try {
                console.log('🔄 GET /api/orgtrack/envios-productores');

                const response = await fetch('/api/orgtrack/envios-productores', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const json = await response.json();
                const envios = json?.data ?? [];

                loading.style.display = 'none';

                if (envios.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                envios.forEach(envio => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${envio.id}</td>
                        <td>${envio.nombre_remitente ?? '-'}</td>
                        <td>${envio.telefono_remitente ?? '-'}</td>
                        <td>${envio.nombre_origen ?? '-'}</td>
                        <td>${envio.nombre_destino ?? '-'}</td>
                        <td>
                            <span class="badge badge-${envio.estado === 'Entregado' ? 'success' : 'warning'}">
                                ${envio.estado}
                            </span>
                        </td>
                        <td></td>
                    `;

                    tbody.appendChild(tr);
                });

                tabla.style.display = 'table';

            } catch (e) {
                console.error('❌ Error:', e);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo obtener la información desde la API';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDatos);
        document.getElementById('btnRecargar')?.addEventListener('click', cargarDatos);
    </script>

@endsection