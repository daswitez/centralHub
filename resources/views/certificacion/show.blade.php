@extends('layouts.app')

@section('page_title', 'Certificado ' . $certificado->codigo_certificado)

@section('page_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">{{ $certificado->codigo_certificado }}</h1>
            <p class="text-muted mb-0">Detalle de Certificación</p>
        </div>
        <div>
            <a href="{{ route('certificaciones.pdf', $certificado->certificado_id) }}" class="btn btn-danger mr-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('certificaciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@endsection

@section('content')

<div class="row">
    {{-- Información Principal --}}
    <div class="col-lg-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-certificate mr-2"></i>Información del Certificado</h3>
            </div>
            <div class="card-body">
                @php
                    $iconoAmbito = match($certificado->ambito) {
                        'CAMPO' => '🌱',
                        'PLANTA' => '🏭',
                        'SALIDA' => '📦',
                        'ENVIO' => '🚛',
                        'GENERAL' => '🏆',
                        default => '📋'
                    };
                    $vigente = !$certificado->vigente_hasta || \Carbon\Carbon::parse($certificado->vigente_hasta)->gte(now());
                @endphp
                
                <div class="text-center mb-4">
                    <span style="font-size: 4rem;">{{ $iconoAmbito }}</span>
                    <h3 class="mt-2">{{ $certificado->ambito }}</h3>
                    @if($vigente)
                        <span class="badge badge-success badge-lg p-2">
                            <i class="fas fa-check-circle"></i> VIGENTE
                        </span>
                    @else
                        <span class="badge badge-danger badge-lg p-2">
                            <i class="fas fa-times-circle"></i> VENCIDO
                        </span>
                    @endif
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Código:</th>
                        <td><code>{{ $certificado->codigo_certificado }}</code></td>
                    </tr>
                    <tr>
                        <th>Área:</th>
                        <td><span class="badge badge-info">{{ $certificado->area }}</span></td>
                    </tr>
                    <tr>
                        <th>Emisor:</th>
                        <td>{{ $certificado->emisor }}</td>
                    </tr>
                    <tr>
                        <th>Vigente desde:</th>
                        <td>{{ $certificado->vigente_desde ? \Carbon\Carbon::parse($certificado->vigente_desde)->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Vigente hasta:</th>
                        <td>{{ $certificado->vigente_hasta ? \Carbon\Carbon::parse($certificado->vigente_hasta)->format('d/m/Y') : 'Sin vencimiento' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        {{-- Resumen de Asociados --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-link mr-2"></i>Elementos Asociados</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        🌱 Lotes de Campo
                        <span class="badge badge-success badge-pill">{{ count($lotes_campo) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        🏭 Lotes de Planta
                        <span class="badge badge-purple badge-pill">{{ count($lotes_planta) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        📦 Lotes de Salida
                        <span class="badge badge-warning badge-pill">{{ count($lotes_salida) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        🚛 Envíos
                        <span class="badge badge-primary badge-pill">{{ count($envios) }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Evidencias Documentales --}}
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Evidencias y Documentos</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-subir-evidencia">
                        <i class="fas fa-plus"></i> Subir Documento
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        @forelse($evidencias as $doc)
                            <tr>
                                <td>
                                    @php
                                        $ext = pathinfo($doc->url_archivo, PATHINFO_EXTENSION);
                                        $icon = match(strtolower($ext)) {
                                            'pdf' => 'fa-file-pdf text-danger',
                                            'jpg', 'jpeg', 'png' => 'fa-file-image text-primary',
                                            'doc', 'docx' => 'fa-file-word text-primary',
                                            default => 'fa-file text-secondary'
                                        };
                                    @endphp
                                    <i class="fas {{ $icon }} fa-lg mr-2"></i>
                                    <a href="{{ $doc->url_archivo }}" target="_blank">{{ $doc->tipo }}</a>
                                    <br>
                                    <small class="text-muted">{{ $doc->descripcion ?? 'Sin descripción' }}</small>
                                </td>
                                <td class="text-right">
                                    <small class="d-block">{{ \Carbon\Carbon::parse($doc->fecha_registro)->format('d/m/Y') }}</small>
                                    <form action="{{ route('certificaciones.evidencia.delete', ['id' => $certificado->certificado_id, 'evidenciaId' => $doc->evidencia_id]) }}" 
                                          method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar documento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted p-3">
                                    <i class="fas fa-folder-open mb-2"></i><br>
                                    No hay documentos adjuntos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    {{-- Timeline de Etapas --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title"><i class="fas fa-stream mr-2"></i>Etapas Certificadas</h3>
            </div>
            <div class="card-body">
                {{-- Timeline --}}
                <div class="timeline">
                    {{-- Etapa Campo --}}
                    <div class="time-label">
                        <span class="bg-success">🌱 Campo</span>
                    </div>
                    @forelse($lotes_campo as $lote)
                        <div>
                            <i class="fas fa-seedling bg-success"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-calendar"></i> 
                                    {{ $lote->fecha_cosecha ? \Carbon\Carbon::parse($lote->fecha_cosecha)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <h3 class="timeline-header">
                                    <a href="#">{{ $lote->codigo_lote_campo }}</a>
                                </h3>
                                <div class="timeline-body">
                                    <strong>Variedad:</strong> {{ $lote->variedad ?? 'N/A' }}<br>
                                    <strong>Productor:</strong> {{ $lote->productor ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>
                            <i class="fas fa-times bg-secondary"></i>
                            <div class="timeline-item">
                                <div class="timeline-body text-muted">
                                    Sin lotes de campo asociados
                                </div>
                            </div>
                        </div>
                    @endforelse
                    
                    {{-- Etapa Planta --}}
                    <div class="time-label">
                        <span class="bg-purple">🏭 Planta</span>
                    </div>
                    @forelse($lotes_planta as $lote)
                        <div>
                            <i class="fas fa-industry bg-purple"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-calendar"></i> 
                                    {{ $lote->fecha_inicio ? \Carbon\Carbon::parse($lote->fecha_inicio)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <h3 class="timeline-header">
                                    <a href="#">{{ $lote->codigo_lote_planta }}</a>
                                </h3>
                                <div class="timeline-body">
                                    <strong>Planta:</strong> {{ $lote->planta ?? 'N/A' }}<br>
                                    <strong>Rendimiento:</strong> {{ $lote->rendimiento_pct ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>
                            <i class="fas fa-times bg-secondary"></i>
                            <div class="timeline-item">
                                <div class="timeline-body text-muted">
                                    Sin lotes de planta asociados
                                </div>
                            </div>
                        </div>
                    @endforelse
                    
                    {{-- Etapa Salida --}}
                    <div class="time-label">
                        <span class="bg-warning">📦 Salida</span>
                    </div>
                    @forelse($lotes_salida as $lote)
                        <div>
                            <i class="fas fa-box bg-warning"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-calendar"></i> 
                                    {{ $lote->fecha_empaque ? \Carbon\Carbon::parse($lote->fecha_empaque)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <h3 class="timeline-header">
                                    <a href="#">{{ $lote->codigo_lote_salida }}</a>
                                </h3>
                                <div class="timeline-body">
                                    <strong>SKU:</strong> {{ $lote->sku ?? 'N/A' }}<br>
                                    <strong>Lote Planta:</strong> {{ $lote->codigo_lote_planta ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>
                            <i class="fas fa-times bg-secondary"></i>
                            <div class="timeline-item">
                                <div class="timeline-body text-muted">
                                    Sin lotes de salida asociados
                                </div>
                            </div>
                        </div>
                    @endforelse
                    
                    {{-- Etapa Envío --}}
                    <div class="time-label">
                        <span class="bg-primary">🚛 Envío</span>
                    </div>
                    @forelse($envios as $envio)
                        <div>
                            <i class="fas fa-truck bg-primary"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    <i class="fas fa-calendar"></i> 
                                    {{ $envio->fecha_salida ? \Carbon\Carbon::parse($envio->fecha_salida)->format('d/m/Y') : 'N/A' }}
                                </span>
                                <h3 class="timeline-header">
                                    <a href="#">{{ $envio->codigo_envio }}</a>
                                    <span class="badge badge-{{ $envio->estado == 'ENTREGADO' ? 'success' : 'info' }}">
                                        {{ $envio->estado }}
                                    </span>
                                </h3>
                                <div class="timeline-body">
                                    <strong>Transportista:</strong> {{ $envio->transportista ?? 'N/A' }}<br>
                                    <strong>Placa:</strong> {{ $envio->placa ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div>
                            <i class="fas fa-times bg-secondary"></i>
                            <div class="timeline-item">
                                <div class="timeline-body text-muted">
                                    Sin envíos asociados
                                </div>
                            </div>
                        </div>
                    @endforelse
                    
                    {{-- Fin del timeline --}}
                    <div>
                        <i class="fas fa-flag-checkered bg-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
    color: white;
}
.badge-purple {
    background-color: #6f42c1;
    color: white;
}
.timeline::before {
    background: linear-gradient(180deg, #28a745, #6f42c1, #ffc107, #007bff, #343a40);
}
</style>

{{-- Modal Subir Evidencia --}}
<div class="modal fade" id="modal-subir-evidencia">
    <div class="modal-dialog">
        <form action="{{ route('certificaciones.evidencia.upload', $certificado->certificado_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title"><i class="fas fa-cloud-upload-alt mr-2"></i>Subir Documento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo de Documento</label>
                        <select name="tipo" class="form-control" required>
                            <option value="Informe de Laboratorio">Informe de Laboratorio</option>
                            <option value="Certificado Físico Escaneado">Certificado Físico Escaneado</option>
                            <option value="Checklist de Inspección">Checklist de Inspección</option>
                            <option value="Fotografía de Evidencia">Fotografía de Evidencia</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" class="form-control" placeholder="Ej: Análisis de residuos lote #123">
                    </div>
                    <div class="form-group">
                        <label>Archivo (PDF, Imagen, Word)</label>
                        <div class="custom-file">
                            <input type="file" name="archivo" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Subir Archivo</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Custom file input label update
document.addEventListener('DOMContentLoaded', function () {
    bsCustomFileInput.init();
});
</script>

@endsection
