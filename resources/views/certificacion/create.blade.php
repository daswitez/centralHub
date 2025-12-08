@extends('layouts.app')

@section('page_title', 'Nueva Certificación')

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-plus-circle text-success"></i> Nueva Certificación</h1>
            <p class="text-muted mb-0">Crear certificación de calidad</p>
        </div>
        <a href="{{ route('certificaciones.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@endsection

@section('content')

<form action="{{ route('certificaciones.store') }}" method="POST">
    @csrf
    
    <div class="row">
        {{-- Información del Certificado --}}
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Información General</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><i class="fas fa-layer-group mr-1"></i>Ámbito <span class="text-danger">*</span></label>
                        <select name="ambito" id="ambito" class="form-control select2" required onchange="actualizarOpciones()">
                            <option value="">Seleccione...</option>
                            @foreach($ambitos as $amb)
                                <option value="{{ $amb }}" {{ old('ambito') == $amb ? 'selected' : '' }}>
                                    @php
                                        $iconoAmbito = match($amb) {
                                            'CAMPO' => '🌱',
                                            'PLANTA' => '🏭',
                                            'SALIDA' => '📦',
                                            'ENVIO' => '🚛',
                                            'GENERAL' => '🏆',
                                            default => '📋'
                                        };
                                    @endphp
                                    {{ $iconoAmbito }} {{ $amb }}
                                </option>
                            @endforeach
                        </select>
                        @error('ambito')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-tag mr-1"></i>Área de Certificación <span class="text-danger">*</span></label>
                        <select name="area" class="form-control" required>
                            <option value="">Seleccione...</option>
                            @foreach($areas as $area)
                                <option value="{{ $area }}" {{ old('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                            @endforeach
                        </select>
                        @error('area')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-building mr-1"></i>Emisor <span class="text-danger">*</span></label>
                        <input type="text" name="emisor" class="form-control" 
                               value="{{ old('emisor') }}" placeholder="Ej: SENASAG, IBNORCA" required>
                        @error('emisor')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt mr-1"></i>Desde <span class="text-danger">*</span></label>
                                <input type="date" name="vigente_desde" class="form-control" 
                                       value="{{ old('vigente_desde', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-times mr-1"></i>Hasta</label>
                                <input type="date" name="vigente_hasta" class="form-control" 
                                       value="{{ old('vigente_hasta') }}">
                                <small class="text-muted">Dejar vacío para sin vencimiento</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Botones de Acción --}}
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success btn-lg btn-block">
                        <i class="fas fa-certificate mr-2"></i> Crear Certificación
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Selección de Elementos a Certificar --}}
        <div class="col-lg-8">
            {{-- Lotes de Campo --}}
            <div class="card" id="card-lotes-campo">
                <div class="card-header bg-success">
                    <h3 class="card-title">🌱 Lotes de Campo</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="row">
                        @foreach($lotes_campo as $lote)
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="lotes_campo[]" value="{{ $lote->lote_campo_id }}" 
                                           class="custom-control-input" id="lc_{{ $lote->lote_campo_id }}">
                                    <label class="custom-control-label" for="lc_{{ $lote->lote_campo_id }}">
                                        <strong>{{ $lote->codigo_lote_campo }}</strong>
                                        <small class="d-block text-muted">
                                            {{ $lote->variedad ?? 'S/V' }} · {{ $lote->productor ?? 'S/P' }}
                                        </small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Lotes de Planta --}}
            <div class="card" id="card-lotes-planta">
                <div class="card-header bg-purple">
                    <h3 class="card-title">🏭 Lotes de Planta</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="row">
                        @foreach($lotes_planta as $lote)
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="lotes_planta[]" value="{{ $lote->lote_planta_id }}" 
                                           class="custom-control-input" id="lp_{{ $lote->lote_planta_id }}">
                                    <label class="custom-control-label" for="lp_{{ $lote->lote_planta_id }}">
                                        <strong>{{ $lote->codigo_lote_planta }}</strong>
                                        <small class="d-block text-muted">
                                            {{ $lote->planta ?? 'S/P' }} · 
                                            {{ $lote->fecha_inicio ? \Carbon\Carbon::parse($lote->fecha_inicio)->format('d/m/Y') : '' }}
                                        </small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Lotes de Salida --}}
            <div class="card" id="card-lotes-salida">
                <div class="card-header bg-warning">
                    <h3 class="card-title">📦 Lotes de Salida</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="row">
                        @foreach($lotes_salida as $lote)
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="lotes_salida[]" value="{{ $lote->lote_salida_id }}" 
                                           class="custom-control-input" id="ls_{{ $lote->lote_salida_id }}">
                                    <label class="custom-control-label" for="ls_{{ $lote->lote_salida_id }}">
                                        <strong>{{ $lote->codigo_lote_salida }}</strong>
                                        <small class="d-block text-muted">
                                            SKU: {{ $lote->sku ?? 'N/A' }} · 
                                            {{ $lote->fecha_empaque ? \Carbon\Carbon::parse($lote->fecha_empaque)->format('d/m/Y') : '' }}
                                        </small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            {{-- Envíos --}}
            <div class="card" id="card-envios">
                <div class="card-header bg-primary">
                    <h3 class="card-title">🚛 Envíos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="row">
                        @foreach($envios as $envio)
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="envios[]" value="{{ $envio->envio_id }}" 
                                           class="custom-control-input" id="env_{{ $envio->envio_id }}">
                                    <label class="custom-control-label" for="env_{{ $envio->envio_id }}">
                                        <strong>{{ $envio->codigo_envio }}</strong>
                                        <small class="d-block text-muted">
                                            {{ $envio->estado }} · 
                                            {{ $envio->fecha_salida ? \Carbon\Carbon::parse($envio->fecha_salida)->format('d/m/Y') : '' }}
                                        </small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
    color: white;
}
.custom-control-label {
    cursor: pointer;
}
</style>

<script>
function actualizarOpciones() {
    const ambito = document.getElementById('ambito').value;
    
    // Mostrar/ocultar cards según el ámbito seleccionado
    document.getElementById('card-lotes-campo').style.display = 
        ['CAMPO', 'GENERAL'].includes(ambito) ? 'block' : 'none';
    document.getElementById('card-lotes-planta').style.display = 
        ['PLANTA', 'GENERAL'].includes(ambito) ? 'block' : 'none';
    document.getElementById('card-lotes-salida').style.display = 
        ['SALIDA', 'GENERAL'].includes(ambito) ? 'block' : 'none';
    document.getElementById('card-envios').style.display = 
        ['ENVIO', 'GENERAL'].includes(ambito) ? 'block' : 'none';
}

// Ejecutar al cargar
document.addEventListener('DOMContentLoaded', actualizarOpciones);
</script>

@endsection
