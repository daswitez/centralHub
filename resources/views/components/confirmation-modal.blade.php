{{--
    Componente: Modal de Confirmación con Preview
    
    Props:
    - $id: string - ID único del modal
    - $title: string - Título del modal
    - $type: string (opcional) - 'warning', 'danger', 'info', 'success'
    - $confirmText: string (opcional, default 'Confirmar')
    - $cancelText: string (opcional, default 'Cancelar')
    - $confirmClass: string (opcional) - clase del botón confirmar
    - $content: slot - contenido del modal
    
    Uso:
    <button data-toggle="modal" data-target="#confirmModal">Abrir</button>
    
    @component('components.confirmation-modal', [
        'id' => 'confirmModal',
        'title' => 'Confirmar Transacción',
        'type' => 'warning'
    ])
        <p>¿Está seguro de realizar esta acción?</p>
        <ul>
            <li>Crear envío: ENV-001</li>
            <li>Mover 3.5 t</li>
        </ul>
    @endcomponent
--}}

@php
    $id = $id ?? 'confirmationModal';
    $title = $title ?? 'Confirmar Acción';
    $type = $type ?? 'info';
    $confirmText = $confirmText ?? 'Confirmar';
    $cancelText = $cancelText ?? 'Cancelar';
    
    $iconMap = [
        'warning' => 'exclamation-triangle',
        'danger' => 'times-circle',
        'info' => 'info-circle',
        'success' => 'check-circle',
    ];
    
    $colorMap = [
        'warning' => 'warning',
        'danger' => 'danger',
        'info' => 'info',
        'success' => 'success',
    ];
    
    $icon = $iconMap[$type] ?? 'info-circle';
    $color = $colorMap[$type] ?? 'info';
    $confirmClass = $confirmClass ?? 'btn-' . $color;
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="{{ $id }}Label">
                    <i class="fas fa-{{ $icon }} text-{{ $color }} mr-2"></i>
                    {{ $title }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ $cancelText }}</button>
                <button type="button" class="btn {{ $confirmClass }}" id="{{ $id }}-confirm">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-header {
        background: #f8f9fa;
    }
    .modal-title {
        font-weight: 600;
    }
</style>
