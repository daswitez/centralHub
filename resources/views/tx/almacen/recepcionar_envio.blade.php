@extends('layouts.app')

@section('page_title', 'Almacén — Recepcionar envío')

@section('page_header')
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
        <div>
            <h1 class="m-0 text-uppercase">Recepcionar envío</h1>
            <p class="text-secondary mb-0 small">Confirma la llegada del envío y actualiza inventario</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 col-lg-10">
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
                            <i class="fas fa-box-open mr-2"></i>Recepción de Envíos
                        </h5>
                        <p class="mb-2">
                            La funcionalidad de <strong>recepcionar envíos en almacén</strong>
                            será gestionada a través de microservicios especializados.
                        </p>
                        <hr>
                        <p class="mb-0 small">
                            <strong>Nota técnica:</strong> Esta vista anteriormente ejecutaba el stored procedure
                            <code>almacen.sp_recepcionar_envio</code> y mostraba un timeline interactivo de trazabilidad.
                            La lógica de negocio será migrada al microservicio de Almacén o Trazabilidad.
                        </p>
                    </div>

                    <h6 class="text-uppercase mb-3">
                        <i class="fas fa-list-ul mr-2"></i>Operaciones disponibles en el futuro:
                    </h6>
                    <ul class="mb-3">
                        <li>Seleccionar envío pendiente de recepción</li>
                        <li>Visualizar timeline de trazabilidad del envío</li>
                        <li>Registrar condiciones de recepción (temperatura, estado del producto)</li>
                        <li>Confirmar recepción y actualizar inventario automáticamente</li>
                        <li>Generar documentación de recepción con firmas digitales</li>
                        <li>Registrar observaciones e incidencias</li>
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