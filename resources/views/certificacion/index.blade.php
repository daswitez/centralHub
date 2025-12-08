@extends('layouts.app')

@section('page_title', 'Certificaciones')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0"><i class="fas fa-certificate text-success"></i> Certificaciones de Calidad</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Certificaciones</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')

{{-- KPIs Cards --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $kpi_vigentes }}</h3>
                <p>Certificados Vigentes</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="?estado=VIGENTE" class="small-box-footer">
                Ver vigentes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $kpi_por_vencer }}</h3>
                <p>Por Vencer (30 días)</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="?estado=POR_VENCER" class="small-box-footer">
                Ver alertas <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $kpi_vencidos }}</h3>
                <p>Vencidos</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="?estado=VENCIDO" class="small-box-footer">
                Ver vencidos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $kpi_generales }}</h3>
                <p>Cert. Generales</p>
            </div>
            <div class="icon">
                <i class="fas fa-medal"></i>
            </div>
            <a href="?ambito=GENERAL" class="small-box-footer">
                Ver generales <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Filtros y Acciones --}}
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros</h3>
        <div class="card-tools">
            <a href="{{ route('certificaciones.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Nueva Certificación
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Ámbito</label>
                    <select name="ambito" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach($ambitos as $amb)
                            <option value="{{ $amb }}" {{ $filtro_ambito == $amb ? 'selected' : '' }}>
                                {{ $amb }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">-- Todos --</option>
                        <option value="VIGENTE" {{ $filtro_estado == 'VIGENTE' ? 'selected' : '' }}>Vigente</option>
                        <option value="POR_VENCER" {{ $filtro_estado == 'POR_VENCER' ? 'selected' : '' }}>Por Vencer</option>
                        <option value="VENCIDO" {{ $filtro_estado == 'VENCIDO' ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <a href="{{ route('certificaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de Certificaciones --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list mr-2"></i>Listado de Certificaciones</h3>
        <div class="card-tools">
            <span class="badge badge-secondary">{{ count($certificados) }} registros</span>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Ámbito</th>
                    <th>Área</th>
                    <th>Emisor</th>
                    <th class="text-center">Vigencia</th>
                    <th class="text-center">Asociados</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificados as $cert)
                    <tr>
                        <td>
                            <a href="{{ route('certificaciones.show', $cert->certificado_id) }}" class="font-weight-bold">
                                {{ $cert->codigo_certificado }}
                            </a>
                        </td>
                        <td>
                            @php
                                $iconoAmbito = match($cert->ambito) {
                                    'CAMPO' => '🌱',
                                    'PLANTA' => '🏭',
                                    'SALIDA' => '📦',
                                    'ENVIO' => '🚛',
                                    'GENERAL' => '🏆',
                                    default => '📋'
                                };
                            @endphp
                            <span class="badge badge-info">{{ $iconoAmbito }} {{ $cert->ambito }}</span>
                        </td>
                        <td>{{ $cert->area }}</td>
                        <td>{{ $cert->emisor }}</td>
                        <td class="text-center small">
                            {{ $cert->vigente_desde ? \Carbon\Carbon::parse($cert->vigente_desde)->format('d/m/Y') : '—' }}
                            <br>→
                            {{ $cert->vigente_hasta ? \Carbon\Carbon::parse($cert->vigente_hasta)->format('d/m/Y') : '∞' }}
                        </td>
                        <td class="text-center">
                            @if($cert->num_lotes_campo > 0)
                                <span class="badge badge-success" title="Lotes Campo">🌱 {{ $cert->num_lotes_campo }}</span>
                            @endif
                            @if($cert->num_lotes_planta > 0)
                                <span class="badge badge-purple" title="Lotes Planta">🏭 {{ $cert->num_lotes_planta }}</span>
                            @endif
                            @if($cert->num_lotes_salida > 0)
                                <span class="badge badge-warning" title="Lotes Salida">📦 {{ $cert->num_lotes_salida }}</span>
                            @endif
                            @if($cert->num_envios > 0)
                                <span class="badge badge-primary" title="Envíos">🚛 {{ $cert->num_envios }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cert->estado === 'VIGENTE')
                                <span class="badge badge-success"><i class="fas fa-check"></i> Vigente</span>
                            @elseif($cert->estado === 'POR_VENCER')
                                <span class="badge badge-warning"><i class="fas fa-clock"></i> Por Vencer</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i> Vencido</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('certificaciones.show', $cert->certificado_id) }}" 
                                   class="btn btn-sm btn-info" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('certificaciones.pdf', $cert->certificado_id) }}" 
                                   class="btn btn-sm btn-danger" title="Exportar PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-certificate fa-3x mb-3 d-block"></i>
                            No hay certificaciones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Card de Ayuda --}}
<div class="card card-outline card-secondary collapsed-card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>¿Cómo funcionan las certificaciones?</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-seedling"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">CAMPO</span>
                        <span class="info-box-number">Etapa 1</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fas fa-industry"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">PLANTA</span>
                        <span class="info-box-number">Etapa 2</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-box"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">SALIDA</span>
                        <span class="info-box-number">Etapa 3</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">ENVÍO</span>
                        <span class="info-box-number">Etapa 4</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="callout callout-info">
            <h5><i class="fas fa-medal"></i> Certificación General</h5>
            <p class="mb-0">
                La certificación general se emite cuando todas las etapas de la cadena (Campo, Planta, Salida, Envío) 
                tienen certificaciones vigentes. Esta certifica la trazabilidad completa del producto.
            </p>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
.badge-purple {
    background-color: #6f42c1;
    color: white;
}
</style>

@endsection
