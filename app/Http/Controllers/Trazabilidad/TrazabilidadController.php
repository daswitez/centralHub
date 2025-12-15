<?php

namespace App\Http\Controllers\Trazabilidad;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;


class TrazabilidadController extends Controller
{
    public function recurso1(): View
    {
        return view('extras.index');
    }
    public function productosIndex(): View
    {
        return view('trazabilidad.productos.index');
    }
    public function pedidosIndex(): View
    {
        return view('trazabilidad.pedidos.index');
    }
    public function pedidosShow(string $id): View
    {
        return view('trazabilidad.pedidos.show', [
            'pedidoId' => $id
        ]);
    }

    public function productosShow(string $id): View
    {
        return view('trazabilidad.productos.show', [
            'productoId' => $id
        ]);
    }
    /**
     * Exportar reporte de trazabilidad a PDF
     */
    public function exportPdf(string $tipo, string $codigo)
    {
        // Obtener datos de trazabilidad directamente (no como JSON)
        $datos = match ($tipo) {
            'campo' => $this->trazabilidadDesdeCampo($codigo),
            'planta' => $this->trazabilidadDesdePlanta($codigo),
            'salida' => $this->trazabilidadDesdeSalida($codigo),
            'orden_envio' => $this->trazabilidadDesdeOrdenEnvio($codigo),
            'envio' => $this->trazabilidadDesdeEnvio($codigo),
            'pedido' => $this->trazabilidadDesdePedido($codigo),
            default => ['error' => 'Tipo no válido']
        };

        if (!isset($datos['etapas'])) {
            abort(404, 'No se encontraron datos de trazabilidad');
        }

        // Contar etapas con datos
        $totalEtapas = 0;
        foreach ($datos['etapas'] as $key => $etapa) {
            if ($etapa !== null && !empty($etapa)) {
                if (is_array($etapa) && isset($etapa[0])) {
                    if (isset($etapa[0]['codigo']))
                        $totalEtapas++;
                } elseif (isset($etapa['codigo'])) {
                    $totalEtapas++;
                }
            }
        }


        // Nombres legibles para tipos
        $tiposLegibles = [
            'campo' => 'Lote de Campo',
            'planta' => 'Lote de Planta',
            'salida' => 'Lote de Salida',
            'envio' => 'Envío',
            'pedido' => 'Pedido',
            'orden_envio' => 'Orden de Envío'
        ];

        $pdf = Pdf::loadView('pdf.trazabilidad', [
            'etapas' => $datos['etapas'],
            'codigoPrincipal' => $codigo,
            'tipoBusqueda' => $tiposLegibles[$tipo] ?? $tipo,
            'totalEtapas' => $totalEtapas
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("trazabilidad_{$codigo}.pdf");
    }
}

