<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Rendimiento de Plantas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; padding: 20px 0; border-bottom: 2px solid #28a745; margin-bottom: 20px; }
        .header h1 { color: #28a745; font-size: 18px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 11px; }
        .filtros { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .filtros-grid { display: table; width: 100%; }
        .filtros-item { display: table-cell; width: 25%; padding: 5px; }
        .filtros-item strong { color: #666; display: block; margin-bottom: 2px; }
        .kpis-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kpis-table td { text-align: center; padding: 10px; border: 1px solid #dee2e6; }
        .kpis-table .kpi-value { font-size: 16px; font-weight: bold; color: #28a745; }
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
        .footer { position: fixed; bottom: 20px; left: 0; right: 0; text-align: center; font-size: 8px; color: #999; }
        .summary-box { border: 1px solid #dee2e6; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .summary-title { font-weight: bold; color: #28a745; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏭 Rendimiento y Productividad de Plantas</h1>
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
                <strong>Planta:</strong>
                {{ $filtros['planta_id'] === 'TODAS' ? 'Todas' : $filtros['planta_id'] }}
            </div>
            <div class="filtros-item">
                <strong>Rendimiento Mín:</strong>
                {{ $filtros['rendimiento_min'] }}%
            </div>
            <div class="filtros-item">
                <strong>Resultados:</strong>
                {{ count($data) }} plantas
            </div>
        </div>
    </div>

    <div class="kpis">
        <table class="kpis-table">
            <tr>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->rendimiento_promedio ?? 0, 1) }}%</div>
                    <div class="kpi-label">Rendimiento Promedio</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->total_lotes ?? 0) }}</div>
                    <div class="kpi-label">Lotes Procesados</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->total_toneladas_salida ?? 0, 1) }} t</div>
                    <div class="kpi-label">Toneladas Producidas</div>
                </td>
                <td style="width: 25%;">
                    <div class="kpi-value">{{ number_format($totales->horas_promedio ?? 0, 1) }}h</div>
                    <div class="kpi-label">Tiempo Promedio Proceso</div>
                </td>
            </tr>
        </table>
    </div>

    @if(count($variedades_procesadas) > 0)
    <div class="summary-box">
        <div class="summary-title">Variedades Procesadas</div>
        <table class="data-table" style="margin: 0;">
            <thead>
                <tr>
                    <th>Variedad</th>
                    <th class="text-center">Lotes</th>
                    <th class="text-right">Toneladas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variedades_procesadas as $v)
                    <tr>
                        <td>{{ $v->variedad }}</td>
                        <td class="text-center">{{ number_format($v->num_lotes) }}</td>
                        <td class="text-right">{{ number_format($v->toneladas, 2) }} t</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section-title">Detalle por Planta</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Planta</th>
                <th style="width: 10%;">Código</th>
                <th class="text-center" style="width: 8%;">Lotes</th>
                <th class="text-center" style="width: 12%;">Rendimiento</th>
                <th class="text-center" style="width: 15%;">Rango</th>
                <th class="text-right" style="width: 10%;">Entrada (t)</th>
                <th class="text-right" style="width: 10%;">Salida (t)</th>
                <th class="text-center" style="width: 10%;">Eficiencia</th>
                <th class="text-center" style="width: 8%;">Horas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    <td><strong>{{ $row->planta }}</strong></td>
                    <td>{{ $row->codigo_planta }}</td>
                    <td class="text-center">{{ number_format($row->total_lotes) }}</td>
                    <td class="text-center">
                        @php 
                            $rend = $row->rendimiento_promedio;
                            $badgeClass = $rend >= 80 ? 'success' : ($rend >= 60 ? 'warning' : 'danger');
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">{{ number_format($rend, 1) }}%</span>
                    </td>
                    <td class="text-center">{{ number_format($row->rendimiento_min ?? 0, 1) }}% - {{ number_format($row->rendimiento_max ?? 0, 1) }}%</td>
                    <td class="text-right">{{ number_format($row->toneladas_entrada ?? 0, 2) }}</td>
                    <td class="text-right">{{ number_format($row->toneladas_salida ?? 0, 2) }}</td>
                    <td class="text-center">
                        @php $eff = $row->eficiencia_conversion ?? 0; @endphp
                        <span class="badge badge-{{ $eff >= 85 ? 'success' : ($eff >= 70 ? 'warning' : 'danger') }}">{{ number_format($eff, 1) }}%</span>
                    </td>
                    <td class="text-center">{{ number_format($row->horas_promedio ?? 0, 1) }}h</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No hay datos para los filtros seleccionados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        CentralHub - Sistema de Trazabilidad Agroindustrial | Documento generado automáticamente
    </div>
</body>
</html>
