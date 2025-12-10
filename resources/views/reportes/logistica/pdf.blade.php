<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Análisis Logístico</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #17a2b8; margin-bottom: 20px; }
        .header h1 { color: #17a2b8; font-size: 18px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 11px; }
        .filtros { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .filtros-grid { display: table; width: 100%; }
        .filtros-item { display: table-cell; width: 25%; padding: 5px; }
        .filtros-item strong { color: #666; display: block; margin-bottom: 2px; }
        .kpis-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kpis-table td { text-align: center; padding: 10px; border: 1px solid #dee2e6; }
        .kpis-table .kpi-value { font-size: 14px; font-weight: bold; color: #17a2b8; }
        .kpis-table .kpi-label { font-size: 9px; color: #666; }
        .section-title { font-size: 12px; font-weight: bold; color: #333; margin: 15px 0 10px; padding-bottom: 5px; border-bottom: 1px solid #dee2e6; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th { background: #343a40; color: white; padding: 8px 5px; text-align: left; font-size: 9px; }
        table.data-table th.text-right { text-align: right; }
        table.data-table th.text-center { text-align: center; }
        table.data-table td { padding: 6px 5px; border-bottom: 1px solid #dee2e6; font-size: 9px; }
        table.data-table tr:nth-child(even) { background: #f8f9fa; }
        table.data-table .text-right { text-align: right; }
        table.data-table .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; color: white; }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; color: #333; }
        .badge-danger { background: #dc3545; }
        .badge-info { background: #17a2b8; }
        .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 8px; color: #999; }
        .summary-box { border: 1px solid #dee2e6; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .summary-title { font-weight: bold; color: #17a2b8; margin-bottom: 5px; }
        .progress-bar { background: #e9ecef; border-radius: 3px; height: 12px; overflow: hidden; }
        .progress-bar-fill { height: 100%; text-align: center; color: white; font-size: 8px; line-height: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚚 Análisis Logístico de Entregas</h1>
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
                <strong>Estado:</strong>
                {{ $filtros['estado'] === 'TODOS' ? 'Todos' : $filtros['estado'] }}
            </div>
            <div class="filtros-item">
                <strong>Transportista:</strong>
                {{ $filtros['transportista_id'] === 'TODOS' ? 'Todos' : $filtros['transportista_id'] }}
            </div>
            <div class="filtros-item">
                <strong>Resultados:</strong>
                {{ count($data) }} registros
            </div>
        </div>
    </div>

    <table class="kpis-table">
        <tr>
            <td style="width: 16%;">
                <div class="kpi-value">{{ number_format($totales->total_envios ?? 0) }}</div>
                <div class="kpi-label">Total Envíos</div>
            </td>
            <td style="width: 16%;">
                <div class="kpi-value" style="color: #28a745;">{{ number_format($totales->entregados ?? 0) }}</div>
                <div class="kpi-label">Entregados</div>
            </td>
            <td style="width: 16%;">
                <div class="kpi-value" style="color: #ffc107;">{{ number_format($totales->en_ruta ?? 0) }}</div>
                <div class="kpi-label">En Ruta</div>
            </td>
            <td style="width: 16%;">
                <div class="kpi-value">{{ number_format($totales->toneladas_totales ?? 0, 1) }} t</div>
                <div class="kpi-label">Toneladas</div>
            </td>
            <td style="width: 16%;">
                <div class="kpi-value">{{ number_format($totales->horas_promedio ?? 0, 1) }}h</div>
                <div class="kpi-label">Tiempo Promedio</div>
            </td>
            <td style="width: 16%;">
                <div class="kpi-value" style="color: #28a745;">{{ number_format($totales->tasa_entrega ?? 0, 1) }}%</div>
                <div class="kpi-label">Tasa de Entrega</div>
            </td>
        </tr>
    </table>

    @if(count($envios_por_estado) > 0)
    <div class="summary-box">
        <div class="summary-title">Resumen por Estado</div>
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-right">Toneladas</th>
                    <th class="text-right">% del Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalEnvios = collect($envios_por_estado)->sum('cantidad'); @endphp
                @foreach($envios_por_estado as $e)
                    <tr>
                        <td>
                            <span class="badge badge-{{ $e->estado === 'ENTREGADO' ? 'success' : ($e->estado === 'EN_RUTA' ? 'warning' : 'info') }}">
                                {{ $e->estado }}
                            </span>
                        </td>
                        <td class="text-center">{{ number_format($e->cantidad) }}</td>
                        <td class="text-right">{{ number_format($e->toneladas, 2) }} t</td>
                        <td class="text-right">{{ $totalEnvios > 0 ? number_format(($e->cantidad / $totalEnvios) * 100, 1) : 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section-title">Desempeño por Transportista</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 18%;">Transportista</th>
                <th style="width: 10%;">Vehículo</th>
                <th class="text-center" style="width: 8%;">Capacidad</th>
                <th class="text-center" style="width: 8%;">Envíos</th>
                <th class="text-center" style="width: 8%;">Entreg.</th>
                <th class="text-center" style="width: 8%;">En Ruta</th>
                <th class="text-right" style="width: 10%;">Toneladas</th>
                <th class="text-center" style="width: 8%;">Tiempo</th>
                <th class="text-center" style="width: 8%;">Temp.</th>
                <th class="text-center" style="width: 14%;">Cumplimiento</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td><strong>{{ $row->transportista }}</strong></td>
                    <td>{{ $row->placa ?? '-' }}</td>
                    <td class="text-center">{{ number_format($row->capacidad_t ?? 0, 1) }} t</td>
                    <td class="text-center">{{ number_format($row->total_envios) }}</td>
                    <td class="text-center"><span class="badge badge-success">{{ $row->entregados ?? 0 }}</span></td>
                    <td class="text-center"><span class="badge badge-warning">{{ $row->en_ruta ?? 0 }}</span></td>
                    <td class="text-right">{{ number_format($row->toneladas_totales ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($row->horas_promedio_entrega ?? 0, 1) }}h</td>
                    <td class="text-center">±{{ number_format($row->variacion_temp_promedio ?? 0, 1) }}°C</td>
                    <td class="text-center">
                        @php $tasa = $row->tasa_cumplimiento ?? 0; @endphp
                        <div class="progress-bar">
                            <div class="progress-bar-fill" style="width: {{ $tasa }}%; background: {{ $tasa >= 80 ? '#28a745' : ($tasa >= 50 ? '#ffc107' : '#dc3545') }};">
                                {{ number_format($tasa, 0) }}%
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay datos para los filtros seleccionados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        CentralHub - Sistema de Trazabilidad Agroindustrial | Documento generado automáticamente
    </div>
</body>
</html>
