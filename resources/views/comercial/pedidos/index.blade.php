@extends('layouts.app')

@section('page_title', 'Pedidos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Listado de Pedidos (desde Trazabilidad)</h3>
                    <button id="btnRecargar" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt"></i> Recargar
                    </button>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Loading spinner --}}
                    <div id="loading" class="text-center py-5">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2 text-muted">Cargando pedidos desde Trazabilidad...</p>
                    </div>

                    {{-- Error message --}}
                    <div id="error" class="text-center py-5 text-danger" style="display: none;">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <p id="errorMessage"></p>
                        <button class="btn btn-sm btn-outline-danger" onclick="cargarPedidos()">
                            <i class="fas fa-redo"></i> Reintentar
                        </button>
                    </div>

                    {{-- Empty state --}}
                    <div id="empty" class="text-center py-5 text-muted" style="display: none;">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>No hay pedidos registrados</p>
                        <p class="small text-muted">
                            <i class="fas fa-info-circle"></i>
                            Los pedidos se gestionan desde el sistema de Trazabilidad
                        </p>
                    </div>

                    {{-- Table --}}
                    <div id="tableContainer" class="table-responsive" style="display: none;">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Items</th>
                                    <th>Monto Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPedidos">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para cargar pedidos desde la API
        async function cargarPedidos() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const tableContainer = document.getElementById('tableContainer');
            const tbody = document.getElementById('tbodyPedidos');

            // Mostrar loading
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            tableContainer.style.display = 'none';

            try {
                console.log('🔄 Iniciando llamada a API de Trazabilidad...');
                console.log('📍 URL:', '/api/trazabilidad/pedidos/completo');

                // Por ahora, listaremos todos los pedidos
                // En producción, deberías filtrar por usuario específico
                const response = await fetch('/api/trazabilidad/pedidos/completo', {
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

                const pedidos = result.data;
                console.log(`📦 Total de pedidos: ${pedidos.length}`);

                // Ocultar loading
                loading.style.display = 'none';

                if (pedidos.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Transformar y mostrar datos
                tbody.innerHTML = '';
                pedidos.forEach(pedido => {
                    tbody.innerHTML += crearFilaPedido(pedido);
                });

                tableContainer.style.display = 'block';

            } catch (err) {
                console.error('❌ Error al cargar pedidos:', err);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerHTML = `
                            <strong>Error al conectar con Trazabilidad</strong><br>
                            ${err.message}<br><br>
                            <small class="text-muted">Verifica la consola del navegador (F12) para más detalles</small>
                        `;
            }
        }

        // Crear fila de tabla
        function crearFilaPedido(pedido) {
            const estadoBadgeMap = {
                'PENDIENTE': 'warning',
                'ABIERTO': 'warning',
                'PREPARANDO': 'info',
                'ENVIADO': 'primary',
                'ENTREGADO': 'success',
                'CANCELADO': 'danger'
            };

            const estadoBadge = estadoBadgeMap[pedido.estado] || 'secondary';
            const fechaFormateada = new Date(pedido.fecha_pedido).toLocaleDateString('es-ES');

            // Calcular totales desde los detalles
            const totalItems = pedido.detalles ? pedido.detalles.length : 0;
            const montoTotal = pedido.detalles ?
                pedido.detalles.reduce((sum, det) => sum + (det.cantidad_t * det.precio_unit_usd), 0) : 0;

            return `
                        <tr>
                            <td>
                                <a href="/comercial/pedidos/${pedido.pedido_id}" class="text-primary font-weight-bold">
                                    ${pedido.codigo_pedido}
                                </a>
                            </td>
                            <td>
                                <small class="text-muted">${pedido.cliente?.codigo_cliente || 'N/A'}</small><br>
                                ${pedido.cliente?.nombre || 'Cliente desconocido'}
                            </td>
                            <td>${fechaFormateada}</td>
                            <td>${totalItems}</td>
                            <td>$${montoTotal.toFixed(2)}</td>
                            <td>
                                <span class="badge badge-${estadoBadge}">${pedido.estado}</span>
                            </td>
                            <td>
                                <a href="/comercial/pedidos/${pedido.pedido_id}" class="btn btn-sm btn-info" title="Ver Detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
        }

        // Cargar al iniciar la página
        document.addEventListener('DOMContentLoaded', cargarPedidos);

        // Botón recargar
        document.getElementById('btnRecargar')?.addEventListener('click', cargarPedidos);
    </script>
@endsection