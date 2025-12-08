<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Trazabilidad - {{ $codigoPrincipal }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px;
            margin: -20px -20px 20px -20px;
            text-align: center;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header p {
            opacity: 0.9;
            font-size: 12px;
        }
        .header .codigo {
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 5px 20px;
            border-radius: 20px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #28a745, #007bff, #ffc107, #dc3545);
        }
        
        .stage {
            position: relative;
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .stage-icon {
            position: absolute;
            left: -30px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
        }
        .stage-content {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-left: 10px;
        }
        .stage-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .stage-title {
            font-size: 14px;
            font-weight: bold;
            color: #495057;
        }
        .stage-code {
            float: right;
            background: #007bff;
            color: white;
            padding: 3px 12px;
            border-radius: 15px;
            font-size: 10px;
        }
        .stage-date {
            font-size: 10px;
            color: #6c757d;
            margin-top: 3px;
        }
        
        .details-grid {
            display: table;
            width: 100%;
        }
        .detail-item {
            display: table-row;
        }
        .detail-label {
            display: table-cell;
            padding: 4px 10px 4px 0;
            color: #6c757d;
            font-size: 10px;
            width: 120px;
        }
        .detail-value {
            display: table-cell;
            padding: 4px 0;
            font-weight: 500;
        }
        
        /* Stage colors */
        .stage-campo .stage-icon { background: #28a745; }
        .stage-campo .stage-code { background: #28a745; }
        
        .stage-planta .stage-icon { background: #6f42c1; }
        .stage-planta .stage-code { background: #6f42c1; }
        
        .stage-salida .stage-icon { background: #fd7e14; }
        .stage-salida .stage-code { background: #fd7e14; }
        
        .stage-orden_envio .stage-icon { background: #17a2b8; }
        .stage-orden_envio .stage-code { background: #17a2b8; }
        
        .stage-envio .stage-icon { background: #007bff; }
        .stage-envio .stage-code { background: #007bff; }
        
        .stage-almacen .stage-icon { background: #20c997; }
        .stage-almacen .stage-code { background: #20c997; }
        
        .stage-pedido .stage-icon { background: #dc3545; }
        .stage-pedido .stage-code { background: #dc3545; }
        
        /* Summary box */
        .summary-box {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box h3 {
            color: #1565c0;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .summary-stats {
            display: table;
            width: 100%;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1565c0;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
        
        /* Certification box */
        .certification {
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }
        .certification h4 {
            color: #28a745;
            margin-bottom: 5px;
        }
        .certification p {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🌿 Reporte de Trazabilidad</h1>
            <p>CentralHub Bolivia - Sistema de Gestión de Producción Agrícola</p>
            <div class="codigo">{{ $codigoPrincipal }}</div>
        </div>

        <!-- Resumen -->
        <div class="summary-box">
            <h3>📊 Resumen de Trazabilidad</h3>
            <div class="summary-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $totalEtapas }}</div>
                    <div class="stat-label">Etapas Rastreadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $tipoBusqueda }}</div>
                    <div class="stat-label">Tipo de Búsqueda</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">✓</div>
                    <div class="stat-label">Verificado</div>
                </div>
            </div>
        </div>

        <!-- Timeline de Etapas -->
        <h3 style="margin-bottom: 15px; color: #495057;">📍 Cadena de Trazabilidad</h3>
        
        <div class="timeline">
            @php
                $etapasConfig = [
                    'campo' => ['nombre' => 'Cosecha', 'icono' => '🌱'],
                    'planta' => ['nombre' => 'Procesamiento', 'icono' => '🏭'],
                    'salida' => ['nombre' => 'Empaque', 'icono' => '📦'],
                    'orden_envio' => ['nombre' => 'Orden de Envío', 'icono' => '📋'],
                    'envio' => ['nombre' => 'Transporte', 'icono' => '🚛'],
                    'almacen' => ['nombre' => 'Almacén', 'icono' => '🏪'],
                    'pedido' => ['nombre' => 'Pedido', 'icono' => '📄']
                ];
                $ordenEtapas = ['campo', 'planta', 'salida', 'orden_envio', 'envio', 'almacen', 'pedido'];
            @endphp

            @foreach($ordenEtapas as $key)
                @if(isset($etapas[$key]))
                    @php
                        $etapaData = $etapas[$key];
                        $config = $etapasConfig[$key];
                        $data = is_array($etapaData) && isset($etapaData[0]) ? $etapaData[0] : $etapaData;
                    @endphp
                    
                    @if($data && isset($data['codigo']))
                        <div class="stage stage-{{ $key }}">
                            <div class="stage-icon">{{ $loop->iteration }}</div>
                            <div class="stage-content">
                                <div class="stage-header">
                                    <span class="stage-code">{{ $data['codigo'] }}</span>
                                    <div class="stage-title">{{ $config['icono'] }} {{ $config['nombre'] }}</div>
                                    <div class="stage-date">
                                        @if(isset($data['fecha']))
                                            {{ \Carbon\Carbon::parse($data['fecha'])->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>
                                
                                @if(isset($data['detalles']) && is_array($data['detalles']))
                                    <div class="details-grid">
                                        @foreach($data['detalles'] as $label => $value)
                                            @if($value && $value !== 'N/A')
                                                <div class="detail-item">
                                                    <span class="detail-label">{{ ucfirst(str_replace('_', ' ', $label)) }}</span>
                                                    <span class="detail-value">{{ $value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>

        <!-- Certificación -->
        <div class="certification">
            <h4>✓ Documento de Trazabilidad Verificado</h4>
            <p>Este documento certifica la cadena de custodia del producto desde su origen hasta su destino.</p>
            <p>Generado automáticamente por el Sistema CentralHub Bolivia</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i:s') }} | CentralHub Bolivia - Sistema de Trazabilidad
        <br>Código de verificación: {{ strtoupper(substr(md5($codigoPrincipal . now()), 0, 8)) }}
    </div>
</body>
</html>
