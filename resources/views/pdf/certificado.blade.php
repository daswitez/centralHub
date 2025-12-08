<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificado {{ $certificado->codigo_certificado }}</title>
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
            line-height: 1.5;
        }
        
        .container {
            padding: 30px;
        }
        
        /* Header con borde decorativo */
        .header {
            border: 4px solid #28a745;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
            background: linear-gradient(135deg, #f8fff8 0%, #e8f5e9 100%);
        }
        
        .header h1 {
            font-size: 28px;
            color: #28a745;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .certificate-code {
            background: #28a745;
            color: white;
            padding: 10px 30px;
            display: inline-block;
            font-size: 18px;
            font-weight: bold;
            border-radius: 25px;
            letter-spacing: 2px;
        }
        
        /* Info principal */
        .main-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .info-box {
            display: table-cell;
            width: 48%;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 20px;
            vertical-align: top;
        }
        
        .info-box:first-child {
            margin-right: 4%;
        }
        
        .info-box h3 {
            font-size: 13px;
            color: #495057;
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 100px;
        }
        
        .info-value {
            color: #333;
        }
        
        /* Ámbito destacado */
        .ambito-badge {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .ambito-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }
        
        .ambito-text {
            background: #17a2b8;
            color: white;
            padding: 8px 25px;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            border-radius: 20px;
        }
        
        /* Lotes asociados */
        .lotes-section {
            margin-bottom: 20px;
        }
        
        .lotes-section h3 {
            font-size: 14px;
            background: #343a40;
            color: white;
            padding: 10px 15px;
            margin-bottom: 0;
            border-radius: 8px 8px 0 0;
        }
        
        .lotes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .lotes-table th {
            background: #6c757d;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        .lotes-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .lotes-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        /* Sello de validez */
        .validity-seal {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 3px double #28a745;
            border-radius: 10px;
        }
        
        .validity-seal.valid {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #28a745;
        }
        
        .validity-seal.expired {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
        }
        
        .validity-seal h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .validity-seal.valid h2 {
            color: #28a745;
        }
        
        .validity-seal.expired h2 {
            color: #dc3545;
        }
        
        /* Firma */
        .signatures {
            display: table;
            width: 100%;
            margin-top: 40px;
        }
        
        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
            padding: 20px;
        }
        
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 10px;
        }
        
        .signature-label {
            font-size: 11px;
            color: #666;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 30px;
            right: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
        
        .verification-code {
            font-family: monospace;
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🌿 Certificado de Calidad</h1>
            <p class="subtitle">CentralHub Bolivia - Sistema de Gestión de Producción Agrícola</p>
            <div class="certificate-code">{{ $certificado->codigo_certificado }}</div>
        </div>
        
        <!-- Ámbito -->
        <div class="ambito-badge">
            @php
                $iconoAmbito = match($certificado->ambito) {
                    'CAMPO' => '🌱',
                    'PLANTA' => '🏭',
                    'SALIDA' => '📦',
                    'ENVIO' => '🚛',
                    'GENERAL' => '🏆',
                    default => '📋'
                };
            @endphp
            <span class="ambito-icon">{{ $iconoAmbito }}</span>
            <span class="ambito-text">CERTIFICACIÓN DE {{ $certificado->ambito }}</span>
        </div>
        
        <!-- Información Principal -->
        <div class="main-info">
            <div class="info-box">
                <h3>Datos del Certificado</h3>
                <div class="info-row">
                    <span class="info-label">Área:</span>
                    <span class="info-value">{{ $certificado->area }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Emisor:</span>
                    <span class="info-value">{{ $certificado->emisor }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vigente desde:</span>
                    <span class="info-value">{{ $certificado->vigente_desde ? \Carbon\Carbon::parse($certificado->vigente_desde)->format('d/m/Y') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vigente hasta:</span>
                    <span class="info-value">{{ $certificado->vigente_hasta ? \Carbon\Carbon::parse($certificado->vigente_hasta)->format('d/m/Y') : 'Sin vencimiento' }}</span>
                </div>
            </div>
            <div class="info-box" style="margin-left: 4%;">
                <h3>Resumen de Cobertura</h3>
                <div class="info-row">
                    <span class="info-label">Lotes Campo:</span>
                    <span class="info-value">{{ count($lotes_campo) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lotes Planta:</span>
                    <span class="info-value">{{ count($lotes_planta) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lotes Salida:</span>
                    <span class="info-value">{{ count($lotes_salida) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Lotes de Campo -->
        @if(count($lotes_campo) > 0)
            <div class="lotes-section">
                <h3>🌱 Lotes de Campo Certificados</h3>
                <table class="lotes-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Variedad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lotes_campo as $lote)
                            <tr>
                                <td><strong>{{ $lote->codigo_lote_campo }}</strong></td>
                                <td>{{ $lote->variedad ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <!-- Lotes de Planta -->
        @if(count($lotes_planta) > 0)
            <div class="lotes-section">
                <h3>🏭 Lotes de Planta Certificados</h3>
                <table class="lotes-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Planta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lotes_planta as $lote)
                            <tr>
                                <td><strong>{{ $lote->codigo_lote_planta }}</strong></td>
                                <td>{{ $lote->planta ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <!-- Lotes de Salida -->
        @if(count($lotes_salida) > 0)
            <div class="lotes-section">
                <h3>📦 Lotes de Salida Certificados</h3>
                <table class="lotes-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>SKU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lotes_salida as $lote)
                            <tr>
                                <td><strong>{{ $lote->codigo_lote_salida }}</strong></td>
                                <td>{{ $lote->sku ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <!-- Sello de Validez -->
        @php
            $vigente = !$certificado->vigente_hasta || \Carbon\Carbon::parse($certificado->vigente_hasta)->gte(now());
        @endphp
        <div class="validity-seal {{ $vigente ? 'valid' : 'expired' }}">
            @if($vigente)
                <h2>✓ CERTIFICADO VIGENTE</h2>
                <p>Este certificado se encuentra activo y válido</p>
            @else
                <h2>✗ CERTIFICADO VENCIDO</h2>
                <p>Este certificado ha expirado</p>
            @endif
        </div>
        
        <!-- Firmas -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <span class="signature-label">{{ $certificado->emisor }}</span>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <span class="signature-label">Responsable de Calidad</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i:s') }} | CentralHub Bolivia - Sistema de Trazabilidad
        <br>
        Código de verificación: <span class="verification-code">{{ strtoupper(substr(md5($certificado->codigo_certificado . $certificado->certificado_id), 0, 12)) }}</span>
    </div>
</body>
</html>
