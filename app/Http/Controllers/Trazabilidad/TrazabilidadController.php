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
        // Obtener datos para los dropdowns de la vista de trazabilidad
        $lotesCampo = DB::select("
            SELECT codigo_lote_campo, fecha_cosecha 
            FROM campo.lotecampo 
            ORDER BY fecha_cosecha DESC 
            LIMIT 100
        ");
        
        $lotesPlanta = DB::select("
            SELECT codigo_lote_planta, fecha_inicio 
            FROM planta.loteplanta 
            ORDER BY fecha_inicio DESC 
            LIMIT 100
        ");
        
        $lotesSalida = DB::select("
            SELECT codigo_lote_salida, fecha_empaque 
            FROM planta.lotesalida 
            ORDER BY fecha_empaque DESC 
            LIMIT 100
        ");
        
        $envios = DB::select("
            SELECT codigo_envio, fecha_salida 
            FROM logistica.envio 
            ORDER BY fecha_salida DESC 
            LIMIT 100
        ");
        
        $pedidos = DB::select("
            SELECT codigo_pedido, fecha_pedido 
            FROM comercial.pedido 
            ORDER BY fecha_pedido DESC 
            LIMIT 100
        ");
        
        $ordenesEnvio = DB::select("
            SELECT codigo_orden, fecha_creacion 
            FROM logistica.orden_envio 
            ORDER BY fecha_creacion DESC 
            LIMIT 100
        ");
        
        return view('extras.index', compact(
            'lotesCampo',
            'lotesPlanta', 
            'lotesSalida',
            'envios',
            'pedidos',
            'ordenesEnvio'
        ));
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
     * API: Obtener datos completos de trazabilidad
     */
    public function getDatosCompletos(string $tipo, string $codigo): JsonResponse
    {
        try {
            $datos = match ($tipo) {
                'campo' => $this->trazabilidadDesdeCampo($codigo),
                'planta' => $this->trazabilidadDesdePlanta($codigo),
                'salida' => $this->trazabilidadDesdeSalida($codigo),
                'orden_envio' => $this->trazabilidadDesdeOrdenEnvio($codigo),
                'envio' => $this->trazabilidadDesdeEnvio($codigo),
                'pedido' => $this->trazabilidadDesdePedido($codigo),
                default => ['error' => 'Tipo no válido']
            };

            if (isset($datos['error'])) {
                return response()->json(['message' => $datos['error']], 404);
            }

            return response()->json($datos);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Trazabilidad desde Lote de Campo
     */
    private function trazabilidadDesdeCampo(string $codigo): array
    {
        $campo = DB::selectOne("
            SELECT lc.*, p.nombre as productor, p.telefono, 
                   v.nombre_comercial as variedad, v.aptitud,
                   m.nombre as municipio, d.nombre as departamento
            FROM campo.lotecampo lc
            JOIN campo.productor p ON p.productor_id = lc.productor_id
            JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            JOIN cat.municipio m ON m.municipio_id = p.municipio_id
            JOIN cat.departamento d ON d.departamento_id = m.departamento_id
            WHERE lc.codigo_lote_campo = ?
        ", [$codigo]);

        if (!$campo) {
            return ['error' => 'Lote de campo no encontrado'];
        }

        return [
            'etapas' => [
                'campo' => [
                    'codigo' => $campo->codigo_lote_campo,
                    'fecha' => $campo->fecha_cosecha,
                    'estado' => 'completed',
                    'detalles' => [
                        'productor' => $campo->productor,
                        'telefono_productor' => $campo->telefono,
                        'variedad' => $campo->variedad,
                        'aptitud' => $campo->aptitud,
                        'ubicacion' => "{$campo->municipio}, {$campo->departamento}",
                        'superficie' => "{$campo->superficie_ha} ha",
                        'fecha_siembra' => $campo->fecha_siembra,
                    ]
                ]
            ]
        ];
    }

    /**
     * Trazabilidad desde Lote de Planta
     */
    private function trazabilidadDesdePlanta(string $codigo): array
    {
        $planta = DB::selectOne("
            SELECT lp.*, p.nombre as planta, p.codigo_planta
            FROM planta.loteplanta lp
            JOIN cat.planta p ON p.planta_id = lp.planta_id
            WHERE lp.codigo_lote_planta = ?
        ", [$codigo]);

        if (!$planta) {
            return ['error' => 'Lote de planta no encontrado'];
        }

        return [
            'etapas' => [
                'planta' => [
                    'codigo' => $planta->codigo_lote_planta,
                    'fecha' => $planta->fecha_inicio,
                    'estado' => 'completed',
                    'detalles' => [
                        'planta' => $planta->planta,
                        'rendimiento' => $planta->rendimiento_pct ? "{$planta->rendimiento_pct}%" : 'N/A',
                        'fecha_inicio' => $planta->fecha_inicio,
                        'fecha_fin' => $planta->fecha_fin ?? 'En proceso',
                    ]
                ]
            ]
        ];
    }

    /**
     * Trazabilidad desde Lote de Salida
     */
    private function trazabilidadDesdeSalida(string $codigo): array
    {
        $salida = DB::selectOne("
            SELECT ls.*, lp.codigo_lote_planta, p.nombre as planta
            FROM planta.lotesalida ls
            JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
            JOIN cat.planta p ON p.planta_id = lp.planta_id
            WHERE ls.codigo_lote_salida = ?
        ", [$codigo]);

        if (!$salida) {
            return ['error' => 'Lote de salida no encontrado'];
        }

        return [
            'etapas' => [
                'salida' => [
                    'codigo' => $salida->codigo_lote_salida,
                    'fecha' => $salida->fecha_empaque,
                    'estado' => 'completed',
                    'detalles' => [
                        'producto' => $salida->sku,
                        'peso_neto' => "{$salida->peso_t} t",
                        'lote_origen' => $salida->codigo_lote_planta,
                        'planta' => $salida->planta,
                        'fecha_empaque' => $salida->fecha_empaque,
                    ]
                ]
            ]
        ];
    }

    /**
     * Trazabilidad desde Orden de Envío
     */
    private function trazabilidadDesdeOrdenEnvio(string $codigo): array
    {
        $orden = DB::selectOne("
            SELECT oe.*, p.nombre as planta_origen, a.nombre as almacen_destino,
                   t.nombre as conductor, v.placa as vehiculo
            FROM logistica.orden_envio oe
            LEFT JOIN cat.planta p ON p.planta_id = oe.planta_origen_id
            LEFT JOIN cat.almacen a ON a.almacen_id = oe.almacen_destino_id
            LEFT JOIN cat.transportista t ON t.transportista_id = oe.transportista_id
            LEFT JOIN cat.vehiculo v ON v.vehiculo_id = oe.vehiculo_id
            WHERE oe.codigo_orden = ?
        ", [$codigo]);

        if (!$orden) {
            return ['error' => 'Orden de envío no encontrada'];
        }

        return [
            'etapas' => [
                'orden_envio' => [
                    'codigo' => $orden->codigo_orden,
                    'fecha' => $orden->fecha_creacion,
                    'estado' => strtolower($orden->estado ?? 'pending'),
                    'detalles' => [
                        'planta_origen' => $orden->planta_origen ?? 'N/A',
                        'almacen_destino' => $orden->almacen_destino ?? 'N/A',
                        'conductor' => $orden->conductor ?? 'Sin asignar',
                        'vehiculo' => $orden->vehiculo ?? 'Sin asignar',
                        'prioridad' => $orden->prioridad ?? 'NORMAL',
                    ]
                ]
            ]
        ];
    }

    /**
     * Trazabilidad desde Envío
     */
    private function trazabilidadDesdeEnvio(string $codigo): array
    {
        $envio = DB::selectOne("
            SELECT e.*, t.nombre as transportista, a.nombre as almacen_origen
            FROM logistica.envio e
            LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
            LEFT JOIN cat.almacen a ON a.almacen_id = e.almacen_origen_id
            WHERE e.codigo_envio = ?
        ", [$codigo]);

        if (!$envio) {
            return ['error' => 'Envío no encontrado'];
        }

        return [
            'etapas' => [
                'envio' => [
                    'codigo' => $envio->codigo_envio,
                    'fecha' => $envio->fecha_salida,
                    'estado' => strtolower($envio->estado ?? 'en_ruta'),
                    'detalles' => [
                        'transportista' => $envio->transportista ?? 'N/A',
                        'almacen' => $envio->almacen_origen ?? 'N/A',
                        'fecha_salida' => $envio->fecha_salida,
                        'estado_envio' => $envio->estado ?? 'EN_RUTA',
                    ]
                ]
            ]
        ];
    }

    /**
     * Trazabilidad desde Pedido
     */
    private function trazabilidadDesdePedido(string $codigo): array
    {
        $pedido = DB::selectOne("
            SELECT p.*, c.nombre as cliente, c.codigo_cliente
            FROM comercial.pedido p
            JOIN cat.cliente c ON c.cliente_id = p.cliente_id
            WHERE p.codigo_pedido = ?
        ", [$codigo]);

        if (!$pedido) {
            return ['error' => 'Pedido no encontrado'];
        }

        return [
            'etapas' => [
                'pedido' => [
                    'codigo' => $pedido->codigo_pedido,
                    'fecha' => $pedido->fecha_pedido,
                    'estado' => strtolower($pedido->estado ?? 'abierto'),
                    'detalles' => [
                        'cliente' => $pedido->cliente,
                        'codigo_cliente' => $pedido->codigo_cliente,
                        'estado' => $pedido->estado ?? 'ABIERTO',
                    ]
                ]
            ]
        ];
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


