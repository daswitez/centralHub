@extends('layouts.app')

@section('page_title', 'Registrar Pedido')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title text-white">
                    <i class="fas fa-info-circle mr-2"></i>Creación de Pedidos
                </h3>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                </div>
                <h4 class="mb-3">Funcionalidad Migrada</h4>
                <p class="text-muted mb-4">
                    La creación de pedidos ahora se gestiona desde el microservicio de <strong>Trazabilidad</strong>.
                </p>
                <p class="text-muted small mb-4">
                    Los pedidos se crean a través de la API del sistema de Trazabilidad, 
                    que ofrece funcionalidades adicionales como múltiples destinos, 
                    aprobación de pedidos y gestión avanzada de productos.
                </p>
                <a href="{{ route('comercial.pedidos.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Listado de Pedidos
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
