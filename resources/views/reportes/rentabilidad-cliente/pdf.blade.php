<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Rentabilidad por Cliente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .filtros {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filtros-grid {
            display: table;
            width: 100%;
        }
        .filtros-item {
            display: table-cell;
            width: 25%;
            padding: 5px;
        }
        .filtros-item strong {
            color: #666;
            display: block;
            margin-bottom: 2px;
        }
        .kpis {
            margin-bottom: 20px;
        }
        .kpis-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kpis-table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .kpis-table .kpi-value {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }
        .kpis-table .kpi-label {
            font-size: 9px;
            color: #666;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin: 15px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background: #343a40;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
        }
        table.data-table th.text-right {
            text-align: right;
        }
        table.data-table th.text-center {
            text-align: center;
        }
        table.data-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #dee2e6;
            font-size: 9px;
        }
        table.data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        table.data-table .text-right {
            text-align: right;
        }
        table.data-table .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            color: white;
        }
        .badge-primary { background: #007bff; }
        .badge-success { background: #28a745; }
        .badge-info { background: #17a2b8; }
        .badge-warning { background: #ffc107; color: #333; }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
        .page-break {
            page-break-after: always;
        }
        .summary-box {
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .summary-title {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Análisis de Rentabilidad por Cliente</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }} | CentralHub - Sistema de Trazabilidad</p>
    </div>

    <div class="filtros">
        <div class="filtros-grid">
            <div class="filtros-item">
                <strong>Período:</strong>
                {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }} - 
                {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}
            </div>
            <div class="filtros-item">
                <strong>Tipo de Cliente:</strong>
                {{ $filtros['tipo_cliente'] === 'TODOS' ? 'Todos' : $filtros['tipo_cliente'] }}
            </div>
            <div class="filtros-item">
                <strong>Límite:</strong>
                Top {{ $filtros['top_n'] }} clientes
            </div>
            <div class="filtros-item">
                <strong>Registros:</strong>
                {{ count($data) }} resultados
            </div>
        </div>
    </div>

    <div class="kpis">
        <table class="kpis-table">
            <tr>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->total_clientes ?? 0) }}</div>
                    <div class="kpi-label">Clientes Activos</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">$ {{ number_format($totales->total_ingresos ?? 0, 2) }}</div>
                    <div class="kpi-label">Ingresos Totales (USD)</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->total_toneladas ?? 0, 2) }} t</div>
                    <div class="kpi-label">Toneladas Vendidas</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">$ {{ number_format($totales->precio_promedio ?? 0, 2) }}</div>
                    <div class="kpi-label">Precio Promedio (USD/t)</div>
                </td>
            </tr>
        </table>
    </div>

    @if(count($ventas_por_tipo) > 0)
    <div class="summary-box">
        <div class="summary-title">Resumen por Tipo de Cliente</div>
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th class="text-center">Pedidos</th>
                    <th class="text-right">Toneladas</th>
                    <th class="text-right">Ingresos USD</th>
                    <th class="text-right">% del Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeneral = collect($ventas_por_tipo)->sum('total_ingresos'); @endphp
                @foreach($ventas_por_tipo as $vt)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $vt->tipo === 'MAYORISTA' ? 'primary' : ($vt->tipo === 'RETAIL' ? 'success' : 'info') }}">
                                {{ $vt->tipo }}
                            </span>
                        </td>
                        <td class="text-center">{{ number_format($vt->num_pedidos) }}</td>
                        <td class="text-right">{{ number_format($vt->total_toneladas, 2) }}</td>
                        <td class="text-right">$ {{ number_format($vt->total_ingresos, 2) }}</td>
                        <td class="text-right">{{ $totalGeneral > 0 ? number_format(($vt->total_ingresos / $totalGeneral) * 100, 1) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section-title">Detalle de Clientes (Ordenado por Ingresos)</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">#</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 10%;">Tipo</th>
                <th style="width: 15%;">Ubicación</th>
                <th class="text-center" style="width: 8%;">Pedidos</th>
                <th class="text-right" style="width: 10%;">Toneladas</th>
                <th class="text-right" style="width: 12%;">Total USD</th>
                <th class="text-right" style="width: 10%;">Precio Prom.</th>
                <th class="text-center" style="width: 10%;">vs Mercado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">
                        @if($index < 3)
                            <span class="badge badge-warning">{{ $index + 1 }}</span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ $row->nombre }}</strong></td>
                    <td>
                        <span class="badge badge-{{ $row->tipo === 'MAYORISTA' ? 'primary' : ($row->tipo === 'RETAIL' ? 'success' : 'info') }}">
                            {{ $row->tipo }}
                        </span>
                    </td>
                    <td>{{ $row->municipio ?? '-' }}{{ $row->departamento ? ', ' . $row->departamento : '' }}</td>
                    <td class="text-center">{{ number_format($row->num_pedidos) }}</td>
                    <td class="text-right">{{ number_format($row->total_toneladas, 2) }}</td>
                    <td class="text-right"><strong>$ {{ number_format($row->total_ingresos, 2) }}</strong></td>
                    <td class="text-right">$ {{ number_format($row->precio_promedio, 2) }}</td>
                    <td class="text-center">
                        @php $diff = $row->diferencia_precio ?? 0; @endphp
                        @if($diff > 0)
                            <span class="positive">+{{ number_format($diff, 2) }}</span>
                        @elseif($diff < 0)
                            <span class="negative">{{ number_format($diff, 2) }}</span>
                        @else
                            0.00
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay datos para los filtros seleccionados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        CentralHub - Sistema de Trazabilidad Agroindustrial | Página 1 | Documento generado automáticamente
    </div>
</body>
</html>
