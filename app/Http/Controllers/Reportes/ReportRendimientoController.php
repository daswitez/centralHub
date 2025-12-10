<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportRendimientoController extends Controller
{
    /**
     * Vista principal del reporte con filtros y gráficos
     */
    public function index(Request $request): View
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(3)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $plantaId = $request->get('planta_id', 'TODAS');
        $rendimientoMin = $request->get('rendimiento_min', 0);

        $data = $this->getReportData($fechaInicio, $fechaFin, $plantaId, $rendimientoMin);
        $plantas = DB::select("SELECT planta_id, nombre, codigo_planta FROM cat.planta ORDER BY nombre");
        $totales = $this->getTotales($fechaInicio, $fechaFin, $plantaId);
        $evolucionMensual = $this->getEvolucionMensual($fechaInicio, $fechaFin, $plantaId);
        $variedadesProcesadas = $this->getVariedadesProcesadas($fechaInicio, $fechaFin, $plantaId);

        return view('reportes.rendimiento-planta.index', [
            'data' => $data,
            'plantas' => $plantas,
            'totales' => $totales,
            'evolucion_mensual' => $evolucionMensual,
            'variedades_procesadas' => $variedadesProcesadas,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'planta_id' => $plantaId,
                'rendimiento_min' => $rendimientoMin,
            ],
        ]);
    }

    /**
     * Exportar a PDF
     */
    public function exportPdf(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(3)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $plantaId = $request->get('planta_id', 'TODAS');
        $rendimientoMin = $request->get('rendimiento_min', 0);

        $data = $this->getReportData($fechaInicio, $fechaFin, $plantaId, $rendimientoMin);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $plantaId);
        $variedadesProcesadas = $this->getVariedadesProcesadas($fechaInicio, $fechaFin, $plantaId);

        $pdf = Pdf::loadView('reportes.rendimiento-planta.pdf', [
            'data' => $data,
            'totales' => $totales,
            'variedades_procesadas' => $variedadesProcesadas,
            'filtros' => [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'planta_id' => $plantaId,
                'rendimiento_min' => $rendimientoMin,
            ],
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('reporte-rendimiento-plantas-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Exportar a Excel con estilos
     */
    public function exportCsv(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonths(3)->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $plantaId = $request->get('planta_id', 'TODAS');
        $rendimientoMin = $request->get('rendimiento_min', 0);

        $data = $this->getReportData($fechaInicio, $fechaFin, $plantaId, $rendimientoMin);
        $totales = $this->getTotales($fechaInicio, $fechaFin, $plantaId);

        $excelData = [];
        foreach ($data as $row) {
            $rend = $row->rendimiento_promedio ?? 0;
            $rendBadge = $rend >= 80 ? 'success' : ($rend >= 60 ? 'warning' : 'danger');
            $eff = $row->eficiencia_conversion ?? 0;
            $effBadge = $eff >= 85 ? 'success' : ($eff >= 70 ? 'warning' : 'danger');

            $excelData[] = [
                $row->planta,
                $row->codigo_planta,
                ['value' => $row->total_lotes, 'class' => 'text-center'],
                ['value' => number_format($rend, 1) . '%', 'badge' => $rendBadge],
                ['value' => number_format($row->rendimiento_min ?? 0, 1) . '% - ' . number_format($row->rendimiento_max ?? 0, 1) . '%', 'class' => 'text-center'],
                ['value' => number_format($row->toneladas_entrada ?? 0, 2), 'class' => 'text-right'],
                ['value' => number_format($row->toneladas_salida ?? 0, 2), 'class' => 'text-right'],
                ['value' => number_format($eff, 1) . '%', 'badge' => $effBadge],
                ['value' => number_format($row->horas_promedio ?? 0, 1) . 'h', 'class' => 'text-center'],
            ];
        }

        $service = new \App\Services\ExcelExportService();

        return $service
            ->setTitle('Reporte de Rendimiento de Plantas')
            ->setPrimaryColor('#28a745')
            ->setSummary([
                'Período' => $fechaInicio . ' a ' . $fechaFin,
                'Planta' => $plantaId === 'TODAS' ? 'Todas' : $plantaId,
                'Rendimiento Mínimo' => $rendimientoMin . '%',
                'Total Plantas' => number_format($totales->total_plantas ?? 0),
                'Total Lotes' => number_format($totales->total_lotes ?? 0),
                'Rendimiento Promedio' => number_format($totales->rendimiento_promedio ?? 0, 1) . '%',
                'Toneladas Producidas' => number_format($totales->total_toneladas_salida ?? 0, 2) . ' t',
                'Tiempo Promedio' => number_format($totales->horas_promedio ?? 0, 1) . ' horas',
            ])
            ->setHeaders([
                'Planta', 'Código', 'Lotes', 'Rendimiento',
                'Rango Rend.', 'Entrada (t)', 'Salida (t)', 'Eficiencia', 'Tiempo Prom.'
            ])
            ->setData($excelData)
            ->download('rendimiento-plantas-' . now()->format('Y-m-d') . '.xls');
    }

    /**
     * Obtener datos del reporte
     */
    private function getReportData(string $fechaInicio, string $fechaFin, string $plantaId, float $rendimientoMin): array
    {
        $plantaCondition = $plantaId !== 'TODAS' ? "AND pl.planta_id = :planta_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'rendimiento_min' => $rendimientoMin,
        ];
        
        if ($plantaId !== 'TODAS') {
            $params['planta_id'] = $plantaId;
        }

        $sql = "
            SELECT 
                pl.planta_id, 
                pl.nombre as planta, 
                pl.codigo_planta,
                count(lp.lote_planta_id) as total_lotes,
                coalesce(avg(lp.rendimiento_pct), 0) as rendimiento_promedio,
                min(lp.rendimiento_pct) as rendimiento_min,
                max(lp.rendimiento_pct) as rendimiento_max,
                coalesce(sum(lpe.peso_entrada_t), 0) as toneladas_entrada,
                coalesce(sum(ls.peso_t), 0) as toneladas_salida,
                CASE 
                    WHEN coalesce(sum(lpe.peso_entrada_t), 0) > 0 
                    THEN (coalesce(sum(ls.peso_t), 0) / sum(lpe.peso_entrada_t)) * 100 
                    ELSE 0 
                END as eficiencia_conversion,
                coalesce(avg(EXTRACT(EPOCH FROM (lp.fecha_fin - lp.fecha_inicio))/3600), 0) as horas_promedio
            FROM cat.planta pl
            LEFT JOIN planta.loteplanta lp ON lp.planta_id = pl.planta_id
                AND lp.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin
            LEFT JOIN planta.lotesalida ls ON ls.lote_planta_id = lp.lote_planta_id
            LEFT JOIN planta.loteplanta_entradacampo lpe ON lpe.lote_planta_id = lp.lote_planta_id
            WHERE 1=1 $plantaCondition
            GROUP BY pl.planta_id, pl.nombre, pl.codigo_planta
            HAVING coalesce(avg(lp.rendimiento_pct), 0) >= :rendimiento_min OR count(lp.lote_planta_id) = 0
            ORDER BY rendimiento_promedio DESC
        ";

        return DB::select($sql, $params);
    }

    /**
     * Obtener totales generales
     */
    private function getTotales(string $fechaInicio, string $fechaFin, string $plantaId): object
    {
        $plantaCondition = $plantaId !== 'TODAS' ? "AND pl.planta_id = :planta_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($plantaId !== 'TODAS') {
            $params['planta_id'] = $plantaId;
        }

        $sql = "
            SELECT 
                count(DISTINCT pl.planta_id) as total_plantas,
                count(lp.lote_planta_id) as total_lotes,
                coalesce(avg(lp.rendimiento_pct), 0) as rendimiento_promedio,
                coalesce(sum(ls.peso_t), 0) as total_toneladas_salida,
                coalesce(avg(EXTRACT(EPOCH FROM (lp.fecha_fin - lp.fecha_inicio))/3600), 0) as horas_promedio
            FROM cat.planta pl
            LEFT JOIN planta.loteplanta lp ON lp.planta_id = pl.planta_id
                AND lp.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin
            LEFT JOIN planta.lotesalida ls ON ls.lote_planta_id = lp.lote_planta_id
            WHERE 1=1 $plantaCondition
        ";

        return DB::selectOne($sql, $params) ?? (object)[
            'total_plantas' => 0,
            'total_lotes' => 0,
            'rendimiento_promedio' => 0,
            'total_toneladas_salida' => 0,
            'horas_promedio' => 0,
        ];
    }

    /**
     * Obtener evolución mensual del rendimiento
     */
    private function getEvolucionMensual(string $fechaInicio, string $fechaFin, string $plantaId): array
    {
        $plantaCondition = $plantaId !== 'TODAS' ? "AND pl.planta_id = :planta_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($plantaId !== 'TODAS') {
            $params['planta_id'] = $plantaId;
        }

        $sql = "
            SELECT 
                to_char(lp.fecha_inicio, 'YYYY-MM') as mes,
                to_char(lp.fecha_inicio, 'Mon YYYY') as mes_label,
                count(lp.lote_planta_id) as total_lotes,
                coalesce(avg(lp.rendimiento_pct), 0) as rendimiento_promedio,
                coalesce(sum(ls.peso_t), 0) as toneladas_salida
            FROM planta.loteplanta lp
            JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            LEFT JOIN planta.lotesalida ls ON ls.lote_planta_id = lp.lote_planta_id
            WHERE lp.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin
            $plantaCondition
            GROUP BY to_char(lp.fecha_inicio, 'YYYY-MM'), to_char(lp.fecha_inicio, 'Mon YYYY')
            ORDER BY mes ASC
        ";

        return DB::select($sql, $params);
    }

    /**
     * Obtener variedades más procesadas
     */
    private function getVariedadesProcesadas(string $fechaInicio, string $fechaFin, string $plantaId): array
    {
        $plantaCondition = $plantaId !== 'TODAS' ? "AND pl.planta_id = :planta_id" : "";
        
        $params = [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ];
        
        if ($plantaId !== 'TODAS') {
            $params['planta_id'] = $plantaId;
        }

        $sql = "
            SELECT 
                v.nombre_comercial as variedad,
                count(DISTINCT lpe.lote_planta_id) as num_lotes,
                coalesce(sum(lpe.peso_entrada_t), 0) as toneladas
            FROM planta.loteplanta_entradacampo lpe
            JOIN planta.loteplanta lp ON lp.lote_planta_id = lpe.lote_planta_id
            JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            JOIN campo.lotecampo lc ON lc.lote_campo_id = lpe.lote_campo_id
            JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            WHERE lp.fecha_inicio BETWEEN :fecha_inicio AND :fecha_fin
            $plantaCondition
            GROUP BY v.variedad_id, v.nombre_comercial
            ORDER BY toneladas DESC
            LIMIT 6
        ";

        return DB::select($sql, $params);
    }
}
