@extends('layouts.app')

@section('page_title', 'Lote de salida y envío')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Lote de salida / Envío</h1>
            <p class="text-secondary mb-0 small">Empaque del lote y opción de crear un envío logístico</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle mr-2"></i>Gestión de Lotes de Salida y Envíos
                    </h3>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    </div>
                    <h4 class="mb-3">Funcionalidad Migrada</h4>
                    <p class="text-muted mb-4">
                        El registro de lotes de salida y envíos ahora se gestiona desde el microservicio de <strong>Trazabilidad</strong>.
                    </p>
                    <p class="text-muted small mb-4">
                        Los lotes de salida (empaque) y envíos se crean a través de la API del sistema de Trazabilidad,
                        que ofrece funcionalidades adicionales como gestión de almacén, movimientos de materiales,
                        y trazabilidad completa del producto desde la producción hasta el almacén.
                    </p>
                    <p class="text-muted small mb-4">
                        <strong>Nota:</strong> Esta funcionalidad anteriormente ejecutaba un Stored Procedure
                        (<code>planta.sp_registrar_lote_salida_y_envio</code>) que manejaba el empaque y creación de envíos.
                        El nuevo flujo en Trazabilidad requiere registrar movimientos de almacén y gestionar el almacenamiento.
                    </p>
                    <a href="{{ route('tx.planta.lotes-salida.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Listado de Lotes de Salida
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
