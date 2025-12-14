@extends('layouts.app')

@section('page_title', 'Registrar lote de planta')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Nuevo lote de planta</h1>
            <p class="text-secondary mb-0 small">Registra un batch de producción y sus entradas de campo</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle mr-2"></i>Gestión de Lotes de Planta
                    </h3>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    </div>
                    <h4 class="mb-3">Funcionalidad Migrada</h4>
                    <p class="text-muted mb-4">
                        El registro de lotes de planta ahora se gestiona desde el microservicio de <strong>Trazabilidad</strong>.
                    </p>
                    <p class="text-muted small mb-4">
                        Los lotes de producción (batches) se crean a través de la API del sistema de Trazabilidad,
                        que ofrece funcionalidades adicionales como asignación de procesos, transformaciones,
                        evaluación de calidad y gestión avanzada de producción.
                    </p>
                    <p class="text-muted small mb-4">
                        <strong>Nota:</strong> Esta funcionalidad anteriormente ejecutaba un Stored Procedure
                        (<code>planta.sp_registrar_lote_planta</code>) que manejaba múltiples entradas de campo.
                        El nuevo flujo en Trazabilidad requiere asignar procesos y registrar transformaciones.
                    </p>
                    <a href="{{ route('tx.planta.lotes-planta.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Listado de Lotes
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
