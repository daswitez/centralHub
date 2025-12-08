<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Guía de Remisión - {{ $orden->codigo_orden }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .logo-section {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        .logo-section h1 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 5px;
        }
        .logo-section p {
            color: #666;
            font-size: 11px;
        }
        .doc-info {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        .doc-number {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            border-radius: 5px;
        }
        .doc-date {
            margin-top: 8px;
            color: #666;
        }
        
        /* Info boxes */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-box {
            display: table-cell;
            width: 48%;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            vertical-align: top;
        }
        .info-box:first-child {
            margin-right: 4%;
        }
        .info-box h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        .info-box p {
            margin-bottom: 5px;
        }
        .info-box strong {
            display: inline-block;
            width: 100px;
            color: #495057;
        }
        
        /* Transport info */
        .transport-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .transport-section h3 {
            color: #1565c0;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .transport-grid {
            display: table;
            width: 100%;
        }
        .transport-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
        }
        .transport-item .icon {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .transport-item .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .transport-item .value {
            font-weight: bold;
            color: #1565c0;
        }
        
        /* Details table */
        .details-section {
            margin-bottom: 20px;
        }
        .details-section h3 {
            font-size: 13px;
            color: #28a745;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #28a745;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .status-pendiente { background: #fff3cd; color: #856404; }
        .status-en_ruta { background: #cce5ff; color: #004085; }
        .status-entregado { background: #d4edda; color: #155724; }
        .status-conductor_asignado { background: #d1ecf1; color: #0c5460; }
        
        /* Priority */
        .priority-urgente {
            background: #f8d7da;
            color: #721c24;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        /* Signatures */
        .signatures {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 30%;
            text-align: center;
            padding: 15px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .signature-label {
            font-size: 10px;
            color: #666;
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
        
        /* QR placeholder */
        .qr-section {
            text-align: center;
            margin-top: 20px;
        }
        .qr-placeholder {
            display: inline-block;
            width: 80px;
            height: 80px;
            border: 2px solid #007bff;
            padding: 5px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="logo-section">
                    <h1>🌿 CentralHub Bolivia</h1>
                    <p>Sistema de Gestión de Producción Agrícola</p>
                    <p>Trazabilidad de Papa - Cadena de Suministro</p>
                </div>
                <div class="doc-info">
                    <div class="doc-number">GUÍA DE REMISIÓN</div>
                    <div class="doc-date">
                        <strong>{{ $orden->codigo_orden }}</strong><br>
                        Fecha: {{ \Carbon\Carbon::parse($orden->fecha_creacion)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado y Prioridad -->
        <div style="margin-bottom: 20px;">
            <span class="status-badge status-{{ strtolower($orden->estado) }}">
                {{ str_replace('_', ' ', $orden->estado) }}
            </span>
            @if($orden->prioridad === 'URGENTE')
                <span class="priority-urgente">⚠️ URGENTE</span>
            @endif
        </div>

        <!-- Origen y Destino -->
        <div class="info-row">
            <div class="info-box">
                <h3>📍 Origen</h3>
                <p><strong>Planta:</strong> {{ $planta->nombre ?? 'N/A' }}</p>
                <p><strong>Código:</strong> {{ $planta->codigo_planta ?? 'N/A' }}</p>
                <p><strong>Ubicación:</strong> {{ $planta->direccion ?? 'N/A' }}</p>
            </div>
            <div class="info-box" style="margin-left: 4%;">
                <h3>🏪 Destino</h3>
                <p><strong>Almacén:</strong> {{ $almacen->nombre ?? 'N/A' }}</p>
                <p><strong>Código:</strong> {{ $almacen->codigo_almacen ?? 'N/A' }}</p>
                @if($zona)
                    <p><strong>Zona:</strong> {{ $zona->nombre ?? 'N/A' }}</p>
                @endif
            </div>
        </div>

        <!-- Información de Transporte -->
        <div class="transport-section">
            <h3>🚛 Información de Transporte</h3>
            <div class="transport-grid">
                <div class="transport-item">
                    <div class="icon">👤</div>
                    <div class="label">Conductor</div>
                    <div class="value">{{ $transportista->nombre ?? 'Sin asignar' }}</div>
                </div>
                <div class="transport-item">
                    <div class="icon">🚛</div>
                    <div class="label">Vehículo</div>
                    <div class="value">{{ $vehiculo->placa ?? 'N/A' }}</div>
                </div>
                <div class="transport-item">
                    <div class="icon">📅</div>
                    <div class="label">Fecha Programada</div>
                    <div class="value">{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('d/m/Y') }}</div>
                </div>
                <div class="transport-item">
                    <div class="icon">⏰</div>
                    <div class="label">Hora Estimada</div>
                    <div class="value">{{ \Carbon\Carbon::parse($orden->fecha_programada)->format('H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Detalle de Carga -->
        <div class="details-section">
            <h3>📦 Detalle de Carga</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lote de Salida</th>
                        <th>Producto (SKU)</th>
                        <th>Peso (t)</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lotes as $index => $lote)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $lote->codigo_lote_salida }}</strong></td>
                            <td>{{ $lote->sku }}</td>
                            <td>{{ number_format($lote->peso_t, 2) }}</td>
                            <td>{{ $lote->observaciones ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #666;">
                                No hay lotes asociados a esta orden
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if(count($lotes) > 0)
                <div style="text-align: right; margin-top: 10px; font-weight: bold;">
                    Total: {{ number_format($lotes->sum('peso_t'), 2) }} toneladas
                </div>
            @endif
        </div>

        <!-- Observaciones -->
        @if($orden->observaciones)
            <div class="info-box" style="display: block; width: 100%;">
                <h3>📝 Observaciones</h3>
                <p>{{ $orden->observaciones }}</p>
            </div>
        @endif

        <!-- Firmas -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <span class="signature-label">Despachado por</span>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <span class="signature-label">Transportista</span>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <span class="signature-label">Recibido por</span>
                </div>
            </div>
        </div>

        <!-- QR Code placeholder -->
        <div class="qr-section">
            <div class="qr-placeholder">
                QR<br>{{ $orden->codigo_orden }}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i:s') }} | CentralHub Bolivia - Sistema de Trazabilidad
        <br>Este documento es válido como guía de remisión interna
    </div>
</body>
</html>
