{{--
    Componente: Indicador de Stock
    
    Props:
    - $available: float - cantidad disponible
    - $unit: string (opcional, default 't') - unidad de medida
    - $label: string (opcional) - etiqueta descriptiva
    - $showBar: bool (opcional, default false) - mostrar barra de progreso
    - $capacity: float (opcional) - capacidad máxima (para barra)
    - $threshold: array (opcional) - ['warning' => 30, 'danger' => 10] - % thresholds
    
    Uso:
    @include('components.stock-indicator', [
        'available' => 15.5,
        'unit' => 't',
        'label' => 'Disponible',
        'showBar' => true,
        'capacity' => 20,
    ])
--}}

@php
    $available = $available ?? 0;
    $unit = $unit ?? 't';
    $label = $label ?? 'Disponible';
    $showBar = $showBar ?? false;
    $capacity = $capacity ?? null;
    $threshold = $threshold ?? ['warning' => 30, 'danger' => 10];
    
    // Calcular porcentaje si hay capacidad
    $percentage = 0;
    $status = 'success';
    if ($capacity && $capacity > 0) {
        $percentage = ($available / $capacity) * 100;
        if ($percentage <= $threshold['danger']) {
            $status = 'danger';
        } elseif ($percentage <= $threshold['warning']) {
            $status = 'warning';
        }
    }
@endphp

<div class="stock-indicator">
    @if($showBar && $capacity)
        {{-- Modo con barra de progreso --}}
        <div class="stock-label-row">
            <span class="stock-label">{{ $label }}</span>
            <span class="stock-value text-{{ $status }}">
                <strong>{{ number_format($available, 2) }}</strong> / {{ number_format($capacity, 2) }} {{ $unit }}
            </span>
        </div>
        <div class="progress" style="height: 8px; margin-top: 0.5rem;">
            <div class="progress-bar bg-{{ $status }}" role="progressbar" 
                 style="width: {{ $percentage }}%"
                 aria-valuenow="{{ $available }}" 
                 aria-valuemin="0" 
                 aria-valuemax="{{ $capacity }}">
            </div>
        </div>
        <div class="stock-percentage">
            <small class="text-muted">{{ number_format($percentage, 1) }}% ocupado</small>
        </div>
    @else
        {{-- Modo simple (solo número) --}}
        <div class="stock-simple">
            @if($label)
                <span class="stock-label-inline">{{ $label }}:</span>
            @endif
            <span class="stock-value-inline">
                <strong>{{ number_format($available, 2) }}</strong> {{ $unit }}
            </span>
            @if($available > 0)
                <i class="fas fa-check-circle text-success ml-1"></i>
            @else
                <i class="fas fa-times-circle text-danger ml-1"></i>
            @endif
        </div>
    @endif
</div>

<style>
    .stock-indicator {
        margin: 0.5rem 0;
    }
    .stock-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }
    .stock-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    .stock-value {
        font-size: 0.875rem;
    }
    .stock-percentage {
        margin-top: 0.25rem;
    }
    .stock-simple {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .stock-label-inline {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .stock-value-inline {
        font-size: 0.9375rem;
    }
</style>
