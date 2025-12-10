<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Estado de Inventario</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #ffc107; margin-bottom: 20px; }
        .header h1 { color: #856404; font-size: 18px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 11px; }
        .filtros { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .filtros-grid { display: table; width: 100%; }
        .filtros-item { display: table-cell; width: 33%; padding: 5px; }
        .filtros-item strong { color: #666; display: block; margin-bottom: 2px; }
        .kpis-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kpis-table td { text-align: center; padding: 10px; border: 1px solid #dee2e6; }
        .kpis-table .kpi-value { font-size: 14px; font-weight: bold; color: #856404; }
        .kpis-table .kpi-label { font-size: 9px; color: #666; }
        .section-title { font-size: 12px; font-weight: bold; color: #333; margin: 15px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #dee2e6; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th { background: #343a40; color: white; padding: 8px 5px; text-align: left; font-size: 9px; }
        table.data-table th.text-right { text-align: right; }
        table.data-table th.text-center { text-align: center; }
        table.data-table td { padding: 6px 5px; border-bottom: 1px solid #dee2e6; font-size: 9px; }
        table.data-table tr:nth-child(even) { background: #f8f9fa; }
        table.data-table tr.critical { background: #f8d7da; }
        table.data-table tr.warning { background: #fff3cd; }
        table.data-table .text-right { text-align: right; }
        table.data-table .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; color: white; }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-danger { background: #dc3545; }
        .badge-info { background: #17a2b8; }
        .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 8px; color: #999; }
        .summary-box { border: 1px solid #dee2e6; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .summary-title { font-weight: bold; color: #856404; margin-bottom: 5px; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Estado de Inventario por Almacén</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i') }} | CentralHub - Sistema de Trazabilidad</p>
    </div>

    <div class="filtros">
        <div class="filtros-grid">
            <div class="filtros-item">
                <strong>Almacén:</strong>
                {{ $filtros['almacen_id'] === 'TODOS' ? 'Todos' : $filtros['almacen_id'] }}
            </div>
            <div class="filtros-item">
                <strong>Nivel de Stock:</strong>
                {{ $filtros['nivel_stock'] === 'TODOS' ? 'Todos' : $filtros['nivel_stock'] }}
            </div>
            <div class="filtros-item">
                <strong>Resultados:</strong>
                {{ count($data) }} items
            </div>
        </div>
    </div>

    <table class="kpis-table">
        <tr>
            <td style="width: 20%;">
                <div class="kpi-value">{{ number_format($totales->total_almacenes ?? 0) }}</div>
                <div class="kpi-label">Almacenes</div>
            </td>
            <td style="width: 20%;">
                <div class="kpi-value">{{ number_format($totales->total_skus ?? 0) }}</div>
                <div class="kpi-label">SKUs Distintos</div>
            </td>
            <td style="width: 20%;">
                <div class="kpi-value">{{ number_format($totales->stock_total ?? 0, 1) }} t</div>
                <div class="kpi-label">Stock Total</div>
            </td>
            <td style="width: 20%;">
                <div class="kpi-value" style="color: #dc3545;">{{ number_format($totales->items_criticos ?? 0) }}</div>
                <div class="kpi-label">Items Críticos</div>
            </td>
            <td style="width: 20%;">
                <div class="kpi-value" style="color: #ffc107;">{{ number_format($totales->items_bajos ?? 0) }}</div>
                <div class="kpi-label">Items Stock Bajo</div>
            </td>
        </tr>
    </table>

    @if(count($stock_por_almacen) > 0)
    <div class="summary-box">
        <div class="summary-title">Resumen por Almacén</div>
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th>Almacén</th>
                    <th>Código</th>
                    <th class="text-center">SKUs</th>
                    <th class="text-right">Stock (t)</th>
                    <th class="text-right">Capacidad (t)</th>
                    <th class="text-right">Ocupación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stock_por_almacen as $a)
                    @php 
                        $ocupacion = ($a->capacidad_total_t && $a->capacidad_total_t > 0) 
                            ? ($a->stock_total / $a->capacidad_total_t) * 100 
                            : 0;
                    @endphp
                    <tr>
                        <td><strong>{{ $a->almacen }}</strong></td>
                        <td>{{ $a->codigo_almacen }}</td>
                        <td class="text-center">{{ $a->num_skus }}</td>
                        <td class="text-right">{{ number_format($a->stock_total, 2) }}</td>
                        <td class="text-right">{{ $a->capacidad_total_t ? number_format($a->capacidad_total_t, 0) : '-' }}</td>
                        <td class="text-right">
                            @if($a->capacidad_total_t)
                                <span class="badge badge-{{ $ocupacion >= 90 ? 'danger' : ($ocupacion >= 70 ? 'warning' : 'success') }}">
                                    {{ number_format($ocupacion, 0) }}%
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section-title">Detalle de Inventario</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Almacén</th>
                <th style="width: 20%;">SKU</th>
                <th class="text-right" style="width: 12%;">Stock Actual</th>
                <th class="text-right" style="width: 12%;">Entradas (30d)</th>
                <th class="text-right" style="width: 12%;">Salidas (30d)</th>
                <th class="text-center" style="width: 10%;">Movimientos</th>
                <th class="text-center" style="width: 10%;">Días Inv.</th>
                <th class="text-center" style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr class="{{ $row->estado_stock === 'CRÍTICO' ? 'critical' : ($row->estado_stock === 'BAJO' ? 'warning' : '') }}">
                    <td><strong>{{ $row->almacen }}</strong></td>
                    <td>{{ $row->sku }}</td>
                    <td class="text-right"><strong>{{ number_format($row->cantidad_actual ?? 0, 2) }} t</strong></td>
                    <td class="text-right text-success">+ {{ number_format($row->entradas_30d ?? 0, 2) }}</td>
                    <td class="text-right text-danger">- {{ number_format($row->salidas_30d ?? 0, 2) }}</td>
                    <td class="text-center">{{ $row->movimientos_30d ?? 0 }}</td>
                    <td class="text-center">
                        @if(($row->dias_inventario ?? 999) >= 999)
                            ∞
                        @else
                            {{ $row->dias_inventario }}
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $row->estado_stock === 'CRÍTICO' ? 'danger' : ($row->estado_stock === 'BAJO' ? 'warning' : ($row->estado_stock === 'ALTO' ? 'info' : 'success')) }}">
                            {{ $row->estado_stock }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay datos para los filtros seleccionados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        CentralHub - Sistema de Trazabilidad Agroindustrial | Documento generado automáticamente
    </div>
</body>
</html>
