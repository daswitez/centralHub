@extends('layouts.app')

@section('page_title', 'Órdenes de Envío')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Órdenes de Envío</h1>
            <p class="text-muted mb-0">Listado de envíos (OrgTrack · solo lectura)</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Remitente</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="envios-table">
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Cargando envíos...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const table = document.getElementById('envios-table');

            try {
                const res = await fetch('/api/orgtrack/envios/all');
                const json = await res.json();

                if (!json.success || !json.data.length) {
                    table.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No existen envíos registrados
                        </td>
                    </tr>`;
                    return;
                }

                table.innerHTML = '';

                json.data.forEach(envio => {
                    table.innerHTML += `
                    <tr>
                        <td><strong>#${envio.id}</strong></td>
                        <td>
                            ${envio.nombre_remitente}<br>
                            <small class="text-muted">${envio.telefono_remitente ?? ''}</small>
                        </td>
                        <td>${envio.direccion_origen}</td>
                        <td>${envio.direccion_destino}</td>
                        <td>
                            <span class="badge badge-info">${envio.estado}</span>
                        </td>
                        <td>${envio.fecha_creacion}</td>
                        <td>
                            <a href="/logistica/ordenes-envio/${envio.id}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                `;
                });

            } catch (e) {
                table.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        Error al cargar envíos
                    </td>
                </tr>`;
                console.error(e);
            }
        });
    </script>
@endpush