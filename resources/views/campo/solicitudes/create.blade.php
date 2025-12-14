@extends('layouts.app')

@section('page_title', 'Nueva Solicitud')

@section('page_header')
    <div>
        <h1 class="m-0">Nueva Solicitud de Producción</h1>
        <p class="text-muted mb-0">Solicitar productos a productores</p>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle mr-2"></i>Gestión de Solicitudes
                    </h3>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    </div>
                    <h4 class="mb-3">Funcionalidad Migrada</h4>
                    <p class="text-muted mb-4">
                        La gestión de solicitudes de producción ahora se maneja desde el microservicio de <strong>OrgTrack</strong>.
                    </p>
                    <p class="text-muted small mb-4">
                        <strong>Nota:</strong> OrgTrack maneja el flujo de envíos de <strong>Productor → Planta</strong>.
                        Si necesitas crear solicitudes de <strong>Planta → Productor</strong>, 
                        esta funcionalidad se gestionará desde el sistema de OrgTrack o se mantendrá en este sistema 
                        según la decisión de arquitectura.
                    </p>
                    <a href="{{ route('solicitudes.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Listado de Solicitudes
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
