@extends('layouts.app')

@section('page_title', 'Detalle del Pedido')

@section('page_header')
    <div>
        <h1 class="m-0">Pedido #{{ $pedidoId }}</h1>
        <p class="text-muted mb-0">
            Detalle completo del pedido
        </p>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    <div class="card">
        <div class="card-body">

            {{-- Loading --}}
            <div id="loading" class="text-center p-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Cargando detalle...</p>
            </div>

            {{-- Error --}}
            <div id="error" class="text-center text-danger p-4" style="display:none;">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <p id="errorMessage"></p>
            </div>

            {{-- Contenido --}}
            <div id="contenido" style="display:none;">

                {{-- Resumen --}}
                <h5>Resumen</h5>
                <ul>
                    <li><strong>N° Pedido:</strong> <span id="numeroPedido"></span></li>
                    <li><strong>Estado:</strong> <span id="estadoPedido"></span></li>
                    <li><strong>Fecha entrega:</strong> <span id="fechaEntrega"></span></li>
                    <li><strong>Total productos:</strong> <span id="totalProductos"></span></li>
                    <li><strong>Subtotal:</strong> Bs. <span id="subtotal"></span></li>
                </ul>

                <hr>

                {{-- Cliente --}}
                <h5>Cliente</h5>
                <ul>
                    <li><strong>Nombre:</strong> <span id="clienteNombre"></span></li>
                    <li><strong>Email:</strong> <span id="clienteEmail"></span></li>
                </ul>

                <hr>

                {{-- Productos --}}
                <h5>Productos</h5>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio total</th>
                        </tr>
                    </thead>
                    <tbody id="productosTable"></tbody>
                </table>

                <hr>

                {{-- Destinos --}}
                <h5>Destinos</h5>
                <div id="destinos"></div>

            </div>

        </div>
    </div>

    <script>
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

                // Pedido
                document.getElementById('numeroPedido').innerText = data.pedido.numero_pedido;
                document.getElementById('estadoPedido').innerText = data.pedido.estado;
                document.getElementById('fechaEntrega').innerText = data.pedido.fecha_entrega;

                // Resumen
                document.getElementById('totalProductos').innerText = data.resumen.total_productos;
                document.getElementById('subtotal').innerText = data.resumen.subtotal;

                // Cliente
                document.getElementById('clienteNombre').innerText = data.cliente.razon_social;
                document.getElementById('clienteEmail').innerText = data.cliente.email;

                // Productos
                const productosTable = document.getElementById('productosTable');
                data.productos.forEach(p => {
                    productosTable.innerHTML += `
                                <tr>
                                    <td>${p.producto.nombre}</td>
                                    <td>${p.cantidad}</td>
                                    <td>Bs. ${p.precio_total}</td>
                                </tr>
                            `;
                });

                // Destinos
                const destinosDiv = document.getElementById('destinos');
                data.destinos.forEach(d => {
                    destinosDiv.innerHTML += `
                                <div class="mb-3">
                                    <strong>Dirección:</strong> ${d.direccion}<br>
                                    <strong>Contacto:</strong> ${d.nombre_contacto}<br>
                                    <strong>Total productos:</strong> ${d.total_productos}
                                </div>
                            `;
                });

                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';

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