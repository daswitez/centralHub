@extends('layouts.app')

@section('page_title', 'Detalle de Envío')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Detalle de Envío</h1>
            <p class="text-muted mb-0">Información del transporte (OrgTrack)</p>
        </div>
        <a href="{{ route('ordenes-envio.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <div id="envio-container">
        <div class="text-center text-muted py-5">
            Cargando información del envío...
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const envioId = "{{ request()->route('id') }}";
            const container = document.getElementById('envio-container');

            try {
                const res = await fetch(`/api/orgtrack/envios/${envioId}/seguimiento`);
                const json = await res.json();

                if (!json.success) {
                    container.innerHTML = `<div class="alert alert-danger">Error al cargar envío</div>`;
                    return;
                }

                const envio = json.data;

                container.innerHTML = `
                <div class="card mb-3">
                    <div class="card-body">
                        <span class="badge badge-primary p-2">
                            ${envio.estado_resumen ?? envio.estado}
                        </span>
                        <hr>
                        <p><strong>Origen:</strong> ${envio.nombre_origen}</p>
                        <p><strong>Destino:</strong> ${envio.nombre_destino}</p>
                    </div>
                </div>

                ${envio.particiones.map(p => `
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Partición #${p.id_asignacion}</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Estado:</strong> ${p.estado}</p>
                            <p><strong>Transportista:</strong> ${p.transportista?.nombre ?? '—'}</p>
                            <p><strong>Vehículo:</strong> ${p.vehiculo?.placa ?? '—'}</p>
                        </div>
                    </div>
                `).join('')}
            `;

            } catch (e) {
                container.innerHTML = `<div class="alert alert-danger">Error inesperado</div>`;
                console.error(e);
            }
        });
    </script>
@endpush