@extends('layouts.app')

@section('page_title', 'Tipos de Transporte - Logística')

@section('page_header')
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-truck-moving text-primary mr-2"></i>Tipos de Transporte
            </h1>
            <p class="text-muted mb-0 mt-1">
                <i class="fas fa-cogs mr-1"></i>Catálogo de vehículos y transportes
            </p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('panel.home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="#">Logística</a></li>
                <li class="breadcrumb-item active">Tipos de Transporte</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @include('components.alerts')

    {{-- KPI simple --}}
    <div class="row mb-3" id="kpiCards" style="display: none;">
        <div class="col-lg-4 col-md-6">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-truck-moving"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tipos de Transporte</span>
                    <span class="info-box-number" id="totalTipos">0</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card principal --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>Catálogo de Tipos de Transporte
            </h3>
            <div class="card-tools">
                <button id="btnRecargar" class="btn btn-sm btn-primary">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Loading --}}
            <div id="loading" class="p-5 text-center">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="sr-only">Cargando...</span>
                </div>
                <p class="mt-3 text-muted mb-0">
                    <i class="fas fa-cloud-download-alt mr-1"></i>Consultando tipos de transporte...
                </p>
            </div>

            {{-- Error --}}
            <div id="error" class="p-5 text-center" style="display:none;">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-4x"></i>
                </div>
                <h5 class="text-danger">Error de conexión</h5>
                <p id="errorMessage" class="text-muted mb-3"></p>
                <button class="btn btn-outline-danger btn-sm" onclick="cargarDatos()">
                    <i class="fas fa-redo mr-1"></i>Reintentar
                </button>
            </div>

            {{-- Empty --}}
            <div id="empty" class="p-5 text-center" style="display:none;">
                <div class="text-muted mb-3">
                    <i class="fas fa-truck fa-4x"></i>
                </div>
                <h5 class="text-muted">Sin tipos de transporte</h5>
                <p class="text-muted mb-0">No hay registros en el catálogo</p>
            </div>

            {{-- Grid de cards --}}
            <div id="gridTipos" class="row p-3" style="display: none;"></div>

        </div>
    </div>

    {{-- Estilos --}}
    <style>
        .transport-card {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }
        .transport-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .transport-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .info-box {
            transition: all 0.3s ease;
        }
        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }
    </style>

    {{-- JavaScript --}}
    <script>
        const iconosPorTipo = {
            'camion': 'fa-truck',
            'camioneta': 'fa-truck-pickup',
            'furgon': 'fa-truck-moving',
            'refrigerado': 'fa-snowflake',
            'moto': 'fa-motorcycle',
            'default': 'fa-shipping-fast'
        };

        const coloresPorIndice = [
            'bg-gradient-primary',
            'bg-gradient-success',
            'bg-gradient-info',
            'bg-gradient-warning',
            'bg-gradient-danger',
            'bg-gradient-secondary'
        ];

        async function cargarDatos() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const empty = document.getElementById('empty');
            const gridTipos = document.getElementById('gridTipos');
            const kpiCards = document.getElementById('kpiCards');

            // Reset UI
            loading.style.display = 'block';
            error.style.display = 'none';
            empty.style.display = 'none';
            gridTipos.style.display = 'none';
            kpiCards.style.display = 'none';
            gridTipos.innerHTML = '';

            try {
                const response = await fetch('/api/orgtrack/tipo-transporte', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const json = await response.json();
                const tipos = json?.data ?? [];

                loading.style.display = 'none';

                if (tipos.length === 0) {
                    empty.style.display = 'block';
                    return;
                }

                // Actualizar KPI
                document.getElementById('totalTipos').textContent = tipos.length;
                kpiCards.style.display = 'flex';
                kpiCards.classList.add('animate-in');

                // Renderizar cards
                tipos.forEach((tipo, index) => {
                    const nombreLower = tipo.nombre?.toLowerCase() || '';
                    let icono = iconosPorTipo['default'];
                    
                    for (const [key, value] of Object.entries(iconosPorTipo)) {
                        if (nombreLower.includes(key)) {
                            icono = value;
                            break;
                        }
                    }

                    const color = coloresPorIndice[index % coloresPorIndice.length];

                    const card = document.createElement('div');
                    card.className = 'col-lg-4 col-md-6 mb-3';
                    card.innerHTML = `
                        <div class="card transport-card h-100 animate-in" style="animation-delay: ${index * 0.1}s">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="transport-icon ${color} text-white mr-3">
                                        <i class="fas ${icono}"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">${tipo.nombre || 'Sin nombre'}</h5>
                                        <span class="badge badge-light">ID: ${tipo.id}</span>
                                    </div>
                                </div>
                                ${tipo.descripcion ? `
                                    <hr class="my-3">
                                    <p class="text-muted mb-0 small">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        ${tipo.descripcion}
                                    </p>
                                ` : ''}
                            </div>
                        </div>
                    `;

                    gridTipos.appendChild(card);
                });

                gridTipos.style.display = 'flex';

            } catch (e) {
                console.error('❌ Error:', e);
                loading.style.display = 'none';
                error.style.display = 'block';
                document.getElementById('errorMessage').innerText =
                    'No se pudo conectar con el servicio de logística';
            }
        }

        document.addEventListener('DOMContentLoaded', cargarDatos);
        document.getElementById('btnRecargar')?.addEventListener('click', cargarDatos);
    </script>
@endsection