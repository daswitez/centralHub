@extends('layouts.app')

@section('page_title', 'Almacén — Despachar a almacén')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Despachar a almacén</h1>
            <p class="text-secondary mb-0 small">Registra un envío desde planta hacia un almacén destino</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-7">
            @include('components.alerts')

            {{-- Mensaje informativo: Funcionalidad gestionada por microservicios --}}
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Funcionalidad Gestionada por Microservicios
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h5 class="alert-heading">
                            <i class="fas fa-warehouse mr-2"></i>Despacho a Almacén
                        </h5>
                        <p class="mb-2">
                            La funcionalidad de <strong>despachar envíos desde planta hacia almacenes</strong>
                            será gestionada a través de microservicios especializados.
                        </p>
                        <hr>
                        <p class="mb-0 small">
                            <strong>Nota técnica:</strong> Esta vista anteriormente ejecutaba el stored procedure
                            <code>almacen.sp_despachar_a_almacen</code>. La lógica de negocio será migrada
                            al microservicio de Almacén o Trazabilidad.
                        </p>
                    </div>

                    <h6 class="text-uppercase mb-3">
                        <i class="fas fa-list-ul mr-2"></i>Operaciones disponibles en el futuro:
                    </h6>
                    <ul class="mb-3">
                        <li>Crear envíos desde planta a almacén destino</li>
                        <li>Asignar transportista y vehículo</li>
                        <li>Registrar detalles de lotes de salida</li>
                        <li>Tracking en tiempo real del envío</li>
                        <li>Actualización automática de inventarios</li>
                    </ul>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Estado actual:</strong> Esta vista está en modo de solo lectura mientras
                        se completa la migración a arquitectura de microservicios.
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('panel.logistica') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver a panel logística
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection