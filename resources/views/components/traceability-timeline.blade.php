{{--
    Componente: Timeline de Trazabilidad
    
    Props:
    - $stages: array de objetos con: ['label', 'icon', 'status', 'date', 'details']
    - $vertical: bool (opcional, default true) - layout vertical u horizontal
    
    Uso:
    @include('components.traceability-timeline', [
        'stages' => [
            ['label' => 'Campo', 'icon' => 'seedling', 'status' => 'completed', 'date' => '20/11/2024', 'details' => 'LC-001'],
            ['label' => 'Planta', 'icon' => 'industry', 'status' => 'completed', 'date' => '25/11/2024'],
            ['label' => 'Almacén', 'icon' => 'warehouse', 'status' => 'in_progress', 'date' => null],
            ['label' => 'Cliente', 'icon' => 'truck', 'status' => 'pending', 'date' => null],
        ],
        'vertical' => true
    ])
--}}

@php
    $vertical = $vertical ?? true;
    $stages = $stages ?? [];
@endphp

@if($vertical)
    {{-- Layout Vertical --}}
    <div class="traceability-timeline">
        @foreach($stages as $index => $stage)
            <div class="timeline-stage {{ $stage['status'] ?? 'pending' }}">
                <div class="timeline-icon-wrapper">
                    <div class="timeline-icon">
                        <i class="fas fa-{{ $stage['icon'] ?? 'circle' }}"></i>
                    </div>
                    @if($index < count($stages) - 1)
                        <div class="timeline-connector"></div>
                    @endif
                </div>
                <div class="timeline-content">
                    <div class="timeline-label">{{ $stage['label'] }}</div>
                    @if(isset($stage['date']) && $stage['date'])
                        <div class="timeline-date">
                            <i class="far fa-clock"></i> {{ $stage['date'] }}
                        </div>
                    @endif
                    @if(isset($stage['details']) && $stage['details'])
                        <div class="timeline-details">{{ $stage['details'] }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .traceability-timeline {
            padding: 1rem 0;
        }
        .timeline-stage {
            display: flex;
            gap: 1rem;
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-stage:last-child {
            padding-bottom: 0;
        }
        .timeline-icon-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            z-index: 2;
            background: #fff;
            border: 3px solid #6c757d;
            color: #6c757d;
        }
        .timeline-stage.completed .timeline-icon {
            border-color: #28a745;
            background: #28a745;
            color: #fff;
        }
        .timeline-stage.in_progress .timeline-icon {
            border-color: #ffc107;
            background: #ffc107;
            color: #000;
            animation: pulse 2s infinite;
        }
        .timeline-stage.pending .timeline-icon {
            border-color: #dee2e6;
            background: #fff;
            color: #adb5bd;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .timeline-connector {
            width: 3px;
            flex-grow: 1;
            margin-top: 0.25rem;
            background: #dee2e6;
        }
        .timeline-stage.completed .timeline-connector {
            background: #28a745;
        }
        .timeline-stage.in_progress .timeline-connector {
            background: linear-gradient(to bottom, #ffc107 0%, #dee2e6 100%);
        }
        .timeline-content {
            flex-grow: 1;
            padding-top: 0.5rem;
        }
        .timeline-label {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }
        .timeline-date {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
        }
        .timeline-details {
            font-size: 0.875rem;
            color: #495057;
            font-family: monospace;
        }
    </style>
@else
    {{-- Layout Horizontal --}}
    <div class="traceability-timeline-horizontal">
        @foreach($stages as $index => $stage)
            <div class="timeline-stage-h {{ $stage['status'] ?? 'pending' }}">
                <div class="timeline-icon-h">
                    <i class="fas fa-{{ $stage['icon'] ?? 'circle' }}"></i>
                </div>
                <div class="timeline-label-h">{{ $stage['label'] }}</div>
                @if($index < count($stages) - 1)
                    <div class="timeline-arrow-h">→</div>
                @endif
            </div>
        @endforeach
    </div>

    <style>
        .traceability-timeline-horizontal {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
        }
        .timeline-stage-h {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 80px;
        }
        .timeline-icon-h {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #6c757d;
            background: #fff;
            color: #6c757d;
        }
        .timeline-stage-h.completed .timeline-icon-h {
            border-color: #28a745;
            background: #28a745;
            color: #fff;
        }
        .timeline-stage-h.in_progress .timeline-icon-h {
            border-color: #ffc107;
            background: #ffc107;
            color: #000;
        }
        .timeline-stage-h.pending .timeline-icon-h {
            border-color: #dee2e6;
            color: #adb5bd;
        }
        .timeline-label-h {
            font-size: 0.875rem;
            font-weight: 600;
            text-align: center;
        }
        .timeline-arrow-h {
            font-size: 1.5rem;
            color: #6c757d;
            margin: 0 0.25rem;
        }
    </style>
@endif
