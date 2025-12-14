@extends('layouts.app')

@section('page_title', 'Mis Solicitudes - Planta')

@section('page_header')
    <div>
        <h1 class="m-0">Solicitudes de Producción</h1>
        <p class="text-muted mb-0">Solicitudes enviadas a productores (desde OrgTrack)</p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listado de Solicitudes</h3>
            <button id="btnRecargar" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt"></i> Recargar
            </button>
        </div>
        <div class="card-body p-0">
            {{-- Loading spinner --}}
            <div id="loading" class="p-4 text-center">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Cargando solicitudes desde OrgTrack...</p>
            </div>

            {{-- Error message --}}
            <div id="error" class="p-4 text-center text-danger" style="display: none;">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <p id="errorMessage"></p>
                <button class="btn btn-sm btn-outline-danger" onclick="cargarSolicitudes()">
                    <i class="fas fa-redo"></i> Reintentar
                </button>
            </div>

            {{-- Empty state --}}
            <div id="empty" class="p-4 text-center text-muted" style="display: none;">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <p>No hay solicitudes registradas</p>
                <p class="small text-muted">
                    <i class="fas fa-info-circle"></i>
                    Las solicitudes se gestionan desde el sistema de OrgTrack
                </p>
            </div>

            {{-- Table --}}
            <table id="tablaSolicitudes" class="table table-hover mb-0" style="display: none;">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Productor</th>
                        <th>Variedad</th>
                        <th>Cantidad (t)</th>
                        <th>Fecha Necesaria</th>
                        <th>Estado</th>
                        <th>Conductor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tbodySolicitudes">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Función para cargar solicitudes desde la API
        async function cargarSolicitudes() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tabla = document.getElementById('tablaSolicitudes');
            const tbody = document.getElementById('tbodySolicitudes');

            // Mostrar loading
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tabla.style.display = 'none';

            try {
                console.log('🔄 Iniciando llamada a API de OrgTrack...');
                console.log('📍 URL:', '/api/orgtrack/envios/all');

                const response = await fetch('/api/orgtrack/envios/all', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });

                console.log('📥 Respuesta recibida:', response.status, response.statusText);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();
                console.log('✅ Datos parseados:', result);

                if (!result.success) {
                    throw new Error(result.error || 'Error desconocido');
                }

                const envios = result.data;
                console.log(`📦 Total de envíos: ${envios.length}`);

                // Ocultar loading
                loading.style.display = 'none';

                if (envios.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Transformar y mostrar datos
                tbody.innerHTML = '';
                envios.forEach(envio => {
                    const solicitud = transformarEnvio(envio);
                    tbody.innerHTML += crearFilaSolicitud(solicitud);
                });

                tabla.style.display = 'table';

            } catch (err) {
                console.error('❌ Error al cargar solicitudes:', err);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerHTML = `
                            <strong>Error al conectar con OrgTrack</strong><br>
                            ${err.message}<br><br>
                            <small class="text-muted">Verifica la consola del navegador (F12) para más detalles</small>
                        `;
            }
        }

        // Transformar datos de OrgTrack al formato esperado
        function transformarEnvio(envio) {
            return {
                solicitud_id: envio.id,
                codigo_solicitud: envio.numero_solicitud || `ENV-${envio.id}`,
                productor_nombre: envio.nombre_remitente,
                variedad_nombre: extraerVariedad(envio),
                cantidad_solicitada_t: calcularPesoTotal(envio),
                fecha_necesaria: envio.fecha_requerida || envio.fecha_creacion,
                estado: mapearEstado(envio.estado),
                conductor_asignado: extraerConductor(envio)
            };
        }

        // Calcular peso total
        function calcularPesoTotal(envio) {
            if (!envio.particiones || !Array.isArray(envio.particiones)) return 0;

            let pesoTotal = 0;
            envio.particiones.forEach(particion => {
                if (particion.cargas && Array.isArray(particion.cargas)) {
                    particion.cargas.forEach(carga => {
                        pesoTotal += carga.peso || 0;
                    });
                }
            });

            return (pesoTotal / 1000).toFixed(3); // kg a toneladas
        }

        // Extraer variedad
        function extraerVariedad(envio) {
            if (!envio.particiones || !Array.isArray(envio.particiones)) return 'N/A';

            for (const particion of envio.particiones) {
                if (particion.cargas && Array.isArray(particion.cargas) && particion.cargas.length > 0) {
                    return particion.cargas[0].nombre_producto || 'N/A';
                }
            }

            return 'N/A';
        }

        // Extraer conductor
        function extraerConductor(envio) {
            if (!envio.particiones || !Array.isArray(envio.particiones)) return null;

            for (const particion of envio.particiones) {
                if (particion.transportista) {
                    const nombre = particion.transportista.nombre || '';
                    const apellido = particion.transportista.apellido || '';
                    return `${nombre} ${apellido}`.trim();
                }
            }

            return null;
        }

        // Mapear estado
        function mapearEstado(estadoOrgTrack) {
            const mapa = {
                'Pendiente': 'PENDIENTE',
                'En curso': 'ACEPTADA',
                'Finalizado': 'COMPLETADA'
            };
            return mapa[estadoOrgTrack] || 'PENDIENTE';
        }

        // Crear fila de tabla
        function crearFilaSolicitud(sol) {
            const estadoBadge = {
                'PENDIENTE': '<span class="badge badge-warning">Pendiente</span>',
                'ACEPTADA': '<span class="badge badge-success">Aceptada</span>',
                'RECHAZADA': '<span class="badge badge-danger">Rechazada</span>',
                'COMPLETADA': '<span class="badge badge-info">Completada</span>'
            };

            const conductorHtml = sol.conductor_asignado
                ? `<i class="fas fa-user-check text-success"></i> ${sol.conductor_asignado}`
                : '<span class="text-muted">-</span>';

            const fechaFormateada = new Date(sol.fecha_necesaria).toLocaleDateString('es-ES');

            return `
                        <tr>
                            <td><strong>${sol.codigo_solicitud}</strong></td>
                            <td>${sol.productor_nombre}</td>
                            <td>${sol.variedad_nombre}</td>
                            <td>${parseFloat(sol.cantidad_solicitada_t).toFixed(2)}</td>
                            <td>${fechaFormateada}</td>
                            <td>${estadoBadge[sol.estado] || sol.estado}</td>
                            <td>${conductorHtml}</td>
                            <td>
                                <a href="/solicitudes/${sol.solicitud_id}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
        }

        // Cargar al iniciar la página
        document.addEventListener('DOMContentLoaded', cargarSolicitudes);

        // Botón recargar
        document.getElementById('btnRecargar')?.addEventListener('click', cargarSolicitudes);
    </script>
@endsection