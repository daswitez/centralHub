@extends('layouts.app')

@section('page_title', 'Detalle del Envío')

@section('page_header')
    <div>
        <h1 class="m-0">Envío #{{ $envioId }}</h1>
        <p class="text-muted mb-0">Detalle completo del envío y trazabilidad</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            {{-- Loading --}}
            <div id="loading" class="text-center p-4">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="mt-2 text-muted">Cargando información del envío...</p>
            </div>

            {{-- Error --}}
            <div id="error" class="alert alert-danger" style="display:none;">
                <strong>Error:</strong>
                <span id="errorMessage"></span>
            </div>

            {{-- Contenido --}}
            <div id="contenido" style="display:none;">

                {{-- Información general --}}
                <h5 class="mb-3">Información General</h5>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>ID Envío</th>
                            <td id="id_envio"></td>
                            <th>Estado</th>
                            <td>
                                <span id="estado" class="badge"></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <td id="cliente"></td>
                            <th>Fecha creación</th>
                            <td id="fecha_creacion"></td>
                        </tr>
                        <tr>
                            <th>Origen</th>
                            <td colspan="3" id="origen"></td>
                        </tr>
                        <tr>
                            <th>Destino</th>
                            <td colspan="3" id="destino"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mb-3 mt-3">
                    <h6 class="mb-2">Mapa Destino</h6>
                    <iframe 
                        id="mapa_frame" 
                        width="100%" 
                        height="350" 
                        frameborder="0" 
                        style="border:0;" 
                        allowfullscreen 
                        aria-hidden="false" 
                        tabindex="0">
                    </iframe>
                </div>

                <hr>

                {{-- Particiones --}}
                <h5 class="mb-3">Particiones / Asignaciones</h5>
                <div id="particiones"></div>

            </div>
        </div>
    </div>

    <script>
        async function cargarDetalle() {

            try {
                const response = await fetch(
                    `/api/orgtrack/envios/{{ $envioId }}/documento`,
                    { headers: { 'Accept': 'application/json' } }
                );

                const json = await response.json();

                if (!response.ok || json.success === false) {
                    throw new Error(json.error ?? 'Error desconocido');
                }

                const data = json.data;

                // Info general
                document.getElementById('id_envio').innerText = data.id_envio;
                document.getElementById('cliente').innerText = data.nombre_cliente ?? '—';
                document.getElementById('fecha_creacion').innerText =
                    new Date(data.fecha_creacion).toLocaleString();

                document.getElementById('origen').innerText = data.nombre_origen;
                document.getElementById('destino').innerText = data.nombre_destino;

                document.getElementById('destino').innerText = data.nombre_destino;

                // Cargar Mapa
                if (data.nombre_destino) {
                    const cleanDest = data.nombre_destino.replace(/\s+/g, '+');
                    const mapUrl = `https://maps.google.com/maps?q=${cleanDest}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
                    document.getElementById('mapa_frame').src = mapUrl;
                }

                const estado = document.getElementById('estado');
                estado.innerText = data.estado;
                estado.className = `badge badge-${data.estado === 'Entregado' ? 'success' : 'warning'}`;

                // Particiones
                const contParticiones = document.getElementById('particiones');
                contParticiones.innerHTML = '';

                data.particiones.forEach(p => {

                    const cargasHtml = p.cargas.map(c => `
                        <li>
                            <strong>${c.tipo}</strong> - ${c.variedad}
                            (${c.cantidad} unidades, ${c.peso} kg)
                        </li>
                    `).join('');

                    const condicionesHtml = p.checklistCondiciones.map(c => `
                        <li>
                            ${c.condicion.titulo}
                            ${c.cumple === null ? '' : c.cumple ? '✅' : '❌'}
                        </li>
                    `).join('');

                    const incidentesHtml = p.checklistIncidentes.map(i => `
                        <li>
                            ${i.tipo_incidente.titulo}
                            ${i.ocurrio ? '⚠️ ' + (i.descripcion ?? '') : '✅'}
                        </li>
                    `).join('');

                    contParticiones.innerHTML += `
                        <div class="card mb-3">
                            <div class="card-header">
                                <strong>Asignación #${p.id_asignacion}</strong>
                                <span class="badge badge-info ml-2">${p.estado}</span>
                            </div>
                            <div class="card-body">

                                <p><strong>Transportista:</strong>
                                    ${p.transportista.nombre} ${p.transportista.apellido}
                                    (${p.transportista.telefono})
                                </p>

                                <p><strong>Vehículo:</strong>
                                    ${p.vehiculo.tipo} - ${p.vehiculo.placa}
                                </p>

                                <p><strong>Tipo transporte:</strong>
                                    ${p.tipo_transporte.nombre}
                                </p>

                                <hr>

                                <h6>Cargas</h6>
                                <ul>${cargasHtml}</ul>

                                <h6>Checklist Condiciones</h6>
                                <ul>${condicionesHtml}</ul>

                                <h6>Checklist Incidentes</h6>
                                <ul>${incidentesHtml}</ul>

                                ${p.firmaTransportista ? `
                                    <div class="mt-4">
                                        <h6 class="text-muted mb-2">Firma Transportista</h6>
                                        <div class="border rounded p-2 d-inline-block bg-white">
                                            <img src="${p.firmaTransportista}" class="img-fluid" style="max-height: 120px;" alt="Firma">
                                        </div>
                                    </div>
                                ` : ''}

                            </div>
                        </div>
                    `;
                });

                document.getElementById('loading').style.display = 'none';
                document.getElementById('contenido').style.display = 'block';

            } catch (e) {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo cargar el detalle del envío';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDetalle);
    </script>
@endsection