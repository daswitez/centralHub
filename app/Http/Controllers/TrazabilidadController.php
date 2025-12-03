<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TrazabilidadController extends Controller
{
    /**
     * Vista principal de trazabilidad
     */
    public function index(): View
    {
        // Cargar todos los lotes disponibles para los dropdowns
        $lotesCampo = DB::table('campo.lotecampo')
            ->select('lote_campo_id', 'codigo_lote_campo', 'fecha_cosecha')
            ->orderBy('fecha_cosecha', 'desc')
            ->get();

        $lotesPlanta = DB::table('planta.loteplanta')
            ->select('lote_planta_id', 'codigo_lote_planta', 'fecha_inicio')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $lotesSalida = DB::table('planta.lotesalida')
            ->select('lote_salida_id', 'codigo_lote_salida', 'fecha_empaque')
            ->orderBy('fecha_empaque', 'desc')
            ->get();

        $envios = DB::table('logistica.envio')
            ->select('envio_id', 'codigo_envio', 'fecha_salida')
            ->orderBy('fecha_salida', 'desc')
            ->get();

        $pedidos = DB::table('comercial.pedido')
            ->select('pedido_id', 'codigo_pedido', 'fecha_pedido')
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return view('trazabilidad.index', compact(
            'lotesCampo',
            'lotesPlanta', 
            'lotesSalida',
            'envios',
            'pedidos'
        ));
    }

    /**
     * Obtener datos completos de trazabilidad
     * API endpoint para cargar trazabilidad dinámicamente
     */
    public function getDatosCompletos(string $tipo, string $codigo): JsonResponse
    {
        try {
            $datos = match($tipo) {
                'campo' => $this->trazabilidadDesdeCampo($codigo),
                'planta' => $this->trazabilidadDesdePlanta($codigo),
                'salida' => $this->trazabilidadDesdeSalida($codigo),
                'envio' => $this->trazabilidadDesdeEnvio($codigo),
                'pedido' => $this->trazabilidadDesdePedido($codigo),
                default => ['error' => 'Tipo no válido']
            };

            if (isset($datos['error'])) {
                return response()->json($datos, 404);
            }

            return response()->json($datos);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener trazabilidad',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trazabilidad desde Lote Campo
     */
    private function trazabilidadDesdeCampo(string $codigo): array
    {
        // 1. Obtener lote de campo
        $loteCampo = DB::table('campo.lotecampo as lc')
            ->select([
                'lc.*',
                'p.nombre as productor_nombre',
                'v.nombre_comercial as variedad_nombre',
                'm.nombre as municipio_nombre'
            ])
            ->leftJoin('campo.productor as p', 'p.productor_id', '=', 'lc.productor_id')
            ->leftJoin('cat.variedadpapa as v', 'v.variedad_id', '=', 'lc.variedad_id')
            ->leftJoin('cat.municipio as m', 'm.municipio_id', '=', 'lc.municipio_id')
            ->where('lc.codigo_lote_campo', $codigo)
            ->first();

        if (!$loteCampo) {
            return ['error' => 'Lote de campo no encontrado'];
        }

        // 2. Lotes de planta asociados
        $lotesPlanta = DB::select("
            SELECT lp.*, pl.nombre as planta_nombre
            FROM planta.loteplanta_entradacampo lpe
            JOIN planta.loteplanta lp ON lp.lote_planta_id = lpe.lote_planta_id
            JOIN cat.planta pl ON pl.planta_id = lp.planta_id
            WHERE lpe.lote_campo_id = ?
        ", [$loteCampo->lote_campo_id]);

        // 3. Lotes de salida
        $lotesSalida = [];
        foreach ($lotesPlanta as $lp) {
            $salidas = DB::select("
                SELECT ls.*, lp.codigo_lote_planta
                FROM planta.lotesalida ls
                JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
                WHERE ls.lote_planta_id = ?
            ", [$lp->lote_planta_id]);
            $lotesSalida = array_merge($lotesSalida, $salidas);
        }

        // 4. Envíos
        $envios = [];
        foreach ($lotesSalida as $ls) {
            $enviosData = DB::select("
                SELECT DISTINCT e.*, ed.cantidad_t
                FROM logistica.enviodetalle ed
                JOIN logistica.envio e ON e.envio_id = ed.envio_id
                WHERE ed.lote_salida_id = ?
            ", [$ls->lote_salida_id]);
            $envios = array_merge($envios, $enviosData);
        }

return [
            'etapas' => [
                'campo' => [
                    'codigo' => $loteCampo->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $loteCampo->fecha_cosecha,
                    'detalles' => [
                        'productor' => $loteCampo->productor_nombre,
                        'variedad' => $loteCampo->variedad_nombre,
                        'municipio' => $loteCampo->municipio_nombre,
                        'superficie_ha' => $loteCampo->superficie_ha
                    ]
                ],
                'planta' => array_map(function($lp) {
                    return [
                        'codigo' => $lp->codigo_lote_planta,
                        'estado' => 'completed',
                        'fecha' => $lp->fecha_inicio,
                        'detalles' => [
                            'planta' => $lp->planta_nombre,
                            'rendimiento_pct' => $lp->rendimiento_pct
                        ]
                    ];
                }, $lotesPlanta),
                'salida' => array_map(function($ls) {
                    return [
                        'codigo' => $ls->codigo_lote_salida,
                        'estado' => 'completed',
                        'fecha' => $ls->fecha_empaque,
                        'detalles' => [
                            'sku' => $ls->sku,
                            'peso_t' => $ls->peso_t
                        ]
                    ];
                }, $lotesSalida),
                'envio' => array_map(function($e) {
                    return [
                        'codigo' => $e->codigo_envio,
                        'estado' => strtolower($e->estado),
                        'fecha' => $e->fecha_salida,
                        'detalles' => [
                            'estado' => $e->estado,
                            'cantidad_t' => $e->cantidad_t
                        ]
                    ];
                }, $envios)
            ]
        ];
    }

    /**
     * Trazabilidad desde Lote Planta
     */
    private function trazabilidadDesdePlanta(string $codigo): array
    {
        // Similar estructura pero desde planta hacia adelante y atrás
        return $this->trazabilidadCompleta('planta', $codigo);
    }

    /**
     * Trazabilidad desde Lote Salida
     */
    private function trazabilidadDesdeSalida(string $codigo): array
    {
        return $this->trazabilidadCompleta('salida', $codigo);
    }

    /**
     * Trazabilidad desde Envío
     */
    private function trazabilidadDesdeEnvio(string $codigo): array
    {
        return $this->trazabilidadCompleta('envio', $codigo);
    }

    /**
     * Trazabilidad desde Pedido
     */
    private function trazabilidadDesdePedido(string $codigo): array
    {
        return $this->trazabilidadCompleta('pedido', $codigo);
    }

    /**
     * Helper para obtener trazabilidad completa
     */
    private function trazabilidadCompleta(string $inicio, string $codigo): array
    {
        // Esta es una versión simplificada
        // En producción, implementar queries recursivos completos
        return [
            'etapas' => [
                'campo' => ['codigo' => 'LC-XXX', 'estado' => 'completed', 'fecha' => date('Y-m-d')],
                'planta' => [['codigo' => 'LP-XXX', 'estado' => 'completed', 'fecha' => date('Y-m-d')]],
                'salida' => [['codigo' => 'LS-XXX', 'estado' => 'completed', 'fecha' => date('Y-m-d')]],
                'envio' => [['codigo' => 'ENV-XXX', 'estado' => 'pending', 'fecha' => date('Y-m-d')]]
            ]
        ];
    }
}
