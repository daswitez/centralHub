@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
    {{-- Tarjeta de ejemplo para validar que AdminLTE 3 está funcionando --}}
    <div class="row" role="region" aria-label="Paneles">
        <div class="col-12 col-md-6">
            <div class="card card-outline card-dark">
                <div class="card-header">
                    <h3 class="card-title">Bienvenida</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">Laravel + AdminLTE 3 instalado correctamente.</p>
                </div>
            </div>
        </div>
    </div>
@endsection


