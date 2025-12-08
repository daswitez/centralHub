<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;


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

        // Órdenes de envío (Planta → Almacén)
        $ordenesEnvio = DB::table('logistica.orden_envio')
            ->select('orden_envio_id', 'codigo_orden', 'fecha_creacion')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $envios = DB::table('logistica.envio')
            ->select('envio_id', 'codigo_envio', 'fecha_salida')
            ->orderBy('fecha_salida', 'desc')
            ->get();

        // Recepciones en almacén
        $recepciones = DB::table('almacen.recepcion as r')
            ->select(['r.recepcion_id', 'r.fecha_recepcion', 'a.nombre as almacen_nombre'])
            ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'r.almacen_id')
            ->orderBy('r.fecha_recepcion', 'desc')
            ->get();

        $pedidos = DB::table('comercial.pedido')
            ->select('pedido_id', 'codigo_pedido', 'fecha_pedido')
            ->orderBy('fecha_pedido', 'desc')
            ->get();

        return view('trazabilidad.index', compact(
            'lotesCampo',
            'lotesPlanta', 
            'lotesSalida',
            'ordenesEnvio',
            'envios',
            'recepciones',
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
                'orden_envio' => $this->trazabilidadDesdeOrdenEnvio($codigo),
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
        // 1. Obtener lote de campo (municipio viene del productor)
        $loteCampo = DB::table('campo.lotecampo as lc')
            ->select([
                'lc.*',
                'p.nombre as productor_nombre',
                'p.telefono as productor_telefono',
                'v.nombre_comercial as variedad_nombre',
                'v.aptitud as variedad_aptitud',
                'm.nombre as municipio_nombre',
                'd.nombre as departamento_nombre'
            ])
            ->leftJoin('campo.productor as p', 'p.productor_id', '=', 'lc.productor_id')
            ->leftJoin('cat.variedadpapa as v', 'v.variedad_id', '=', 'lc.variedad_id')
            ->leftJoin('cat.municipio as m', 'm.municipio_id', '=', 'p.municipio_id')
            ->leftJoin('cat.departamento as d', 'd.departamento_id', '=', 'm.departamento_id')
            ->where('lc.codigo_lote_campo', $codigo)
            ->first();

        if (!$loteCampo) {
            return ['error' => 'Lote de campo no encontrado'];
        }

        // 2. Lotes de planta asociados
        $lotesPlanta = DB::select("
            SELECT lp.*, pl.nombre as planta_nombre, pl.codigo_planta
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
                SELECT DISTINCT e.*, ed.cantidad_t,
                       t.nombre as transportista_nombre,
                       v.placa as vehiculo_placa
                FROM logistica.enviodetalle ed
                JOIN logistica.envio e ON e.envio_id = ed.envio_id
                LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
                LEFT JOIN cat.vehiculo v ON v.vehiculo_id = e.vehiculo_id
                WHERE ed.lote_salida_id = ?
            ", [$ls->lote_salida_id]);
            $envios = array_merge($envios, $enviosData);
        }

        // 5. Recepciones en almacén
        $recepciones = [];
        foreach ($envios as $env) {
            $recData = DB::table('almacen.recepcion as r')
                ->select(['r.*', 'a.nombre as almacen_nombre', 'z.nombre as zona_nombre'])
                ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'r.almacen_id')
                ->leftJoin('almacen.zona as z', 'z.zona_id', '=', 'r.zona_id')
                ->where('r.envio_id', $env->envio_id)
                ->first();
            if ($recData) {
                $recepciones[] = $recData;
            }
        }

        return [
            'etapas' => [
                'campo' => [
                    'codigo' => $loteCampo->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $loteCampo->fecha_cosecha,
                    'detalles' => [
                        'productor' => $loteCampo->productor_nombre,
                        'telefono_productor' => $loteCampo->productor_telefono ?? 'N/A',
                        'variedad' => $loteCampo->variedad_nombre,
                        'aptitud' => $loteCampo->variedad_aptitud ?? 'N/A',
                        'ubicacion' => ($loteCampo->municipio_nombre ?? '') . ', ' . ($loteCampo->departamento_nombre ?? ''),
                        'superficie' => ($loteCampo->superficie_ha ?? 0) . ' ha',
                        'peso_cosechado' => ($loteCampo->peso_t ?? 0) . ' toneladas',
                        'fecha_siembra' => $loteCampo->fecha_siembra ?? 'N/A'
                    ]
                ],
                'planta' => array_map(function($lp) {
                    return [
                        'codigo' => $lp->codigo_lote_planta,
                        'estado' => 'completed',
                        'fecha' => $lp->fecha_inicio,
                        'detalles' => [
                            'planta' => $lp->planta_nombre . ' (' . $lp->codigo_planta . ')',
                            'rendimiento' => ($lp->rendimiento_pct ?? 0) . '%',
                            'fecha_inicio' => $lp->fecha_inicio,
                            'fecha_fin' => $lp->fecha_fin ?? 'En proceso'
                        ]
                    ];
                }, $lotesPlanta),

                'salida' => array_map(function($ls) {
                    return [
                        'codigo' => $ls->codigo_lote_salida,
                        'estado' => 'completed',
                        'fecha' => $ls->fecha_empaque,
                        'detalles' => [
                            'producto' => $ls->sku,
                            'peso_neto' => ($ls->peso_t ?? 0) . ' toneladas',
                            'lote_origen' => $ls->codigo_lote_planta ?? 'N/A',
                            'fecha_empaque' => $ls->fecha_empaque,
                            'fecha_vencimiento' => $ls->fecha_vencimiento ?? 'N/A'
                        ]
                    ];
                }, $lotesSalida),
                'envio' => array_map(function($e) {
                    return [
                        'codigo' => $e->codigo_envio,
                        'estado' => strtolower($e->estado ?? 'pending'),
                        'fecha' => $e->fecha_salida,
                        'detalles' => [
                            'estado_envio' => $e->estado ?? 'PENDIENTE',
                            'cantidad' => ($e->cantidad_t ?? 0) . ' toneladas',
                            'conductor' => $e->transportista_nombre ?? 'Sin asignar',
                            'vehiculo' => $e->vehiculo_placa ?? 'Sin asignar',
                            'fecha_salida' => $e->fecha_salida ?? 'Pendiente'
                        ]
                    ];
                }, $envios),
                'almacen' => array_map(function($r) {
                    return [
                        'codigo' => 'REC-' . $r->recepcion_id,
                        'estado' => 'completed',
                        'fecha' => $r->fecha_recepcion,
                        'detalles' => [
                            'almacen' => $r->almacen_nombre ?? 'N/A',
                            'zona' => $r->zona_nombre ?? 'Sin asignar',
                            'fecha_recepcion' => $r->fecha_recepcion,
                            'observaciones' => $r->observacion ?? 'Sin observaciones'
                        ]
                    ];
                }, $recepciones)
            ]
        ];
    }


    /**
     * Trazabilidad desde Lote Planta
     */
    private function trazabilidadDesdePlanta(string $codigo): array
    {
        // 1. Obtener lote de planta
        $lotePlanta = DB::table('planta.loteplanta as lp')
            ->select(['lp.*', 'pl.nombre as planta_nombre'])
            ->leftJoin('cat.planta as pl', 'pl.planta_id', '=', 'lp.planta_id')
            ->where('lp.codigo_lote_planta', $codigo)
            ->first();

        if (!$lotePlanta) {
            return ['error' => 'Lote de planta no encontrado'];
        }

        // 2. Lotes de campo origen
        $lotesCampo = DB::select("
            SELECT lc.*, p.nombre as productor_nombre, v.nombre_comercial as variedad_nombre
            FROM planta.loteplanta_entradacampo lpe
            JOIN campo.lotecampo lc ON lc.lote_campo_id = lpe.lote_campo_id
            JOIN campo.productor p ON p.productor_id = lc.productor_id
            JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            WHERE lpe.lote_planta_id = ?
        ", [$lotePlanta->lote_planta_id]);

        // 3. Lotes de salida
        $lotesSalida = DB::select("
            SELECT ls.*
            FROM planta.lotesalida ls
            WHERE ls.lote_planta_id = ?
        ", [$lotePlanta->lote_planta_id]);

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
                'campo' => count($lotesCampo) > 0 ? [
                    'codigo' => $lotesCampo[0]->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $lotesCampo[0]->fecha_cosecha,
                    'detalles' => [
                        'productor' => $lotesCampo[0]->productor_nombre,
                        'variedad' => $lotesCampo[0]->variedad_nombre,
                        'superficie_ha' => $lotesCampo[0]->superficie_ha
                    ]
                ] : null,
                'planta' => [[
                    'codigo' => $lotePlanta->codigo_lote_planta,
                    'estado' => 'completed',
                    'fecha' => $lotePlanta->fecha_inicio,
                    'detalles' => [
                        'planta' => $lotePlanta->planta_nombre,
                        'rendimiento_pct' => $lotePlanta->rendimiento_pct
                    ]
                ]],
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
     * Trazabilidad desde Lote Salida
     */
    private function trazabilidadDesdeSalida(string $codigo): array
    {
        // 1. Obtener lote de salida
        $loteSalida = DB::table('planta.lotesalida as ls')
            ->select(['ls.*'])
            ->where('ls.codigo_lote_salida', $codigo)
            ->first();

        if (!$loteSalida) {
            return ['error' => 'Lote de salida no encontrado'];
        }

        // 2. Lote de planta
        $lotePlanta = DB::table('planta.loteplanta as lp')
            ->select(['lp.*', 'pl.nombre as planta_nombre'])
            ->leftJoin('cat.planta as pl', 'pl.planta_id', '=', 'lp.planta_id')
            ->where('lp.lote_planta_id', $loteSalida->lote_planta_id)
            ->first();

        // 3. Lotes de campo
        $lotesCampo = [];
        if ($lotePlanta) {
            $lotesCampo = DB::select("
                SELECT lc.*, p.nombre as productor_nombre, v.nombre_comercial as variedad_nombre
                FROM planta.loteplanta_entradacampo lpe
                JOIN campo.lotecampo lc ON lc.lote_campo_id = lpe.lote_campo_id
                JOIN campo.productor p ON p.productor_id = lc.productor_id
                JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
                WHERE lpe.lote_planta_id = ?
            ", [$lotePlanta->lote_planta_id]);
        }

        // 4. Envíos
        $envios = DB::select("
            SELECT DISTINCT e.*, ed.cantidad_t
            FROM logistica.enviodetalle ed
            JOIN logistica.envio e ON e.envio_id = ed.envio_id
            WHERE ed.lote_salida_id = ?
        ", [$loteSalida->lote_salida_id]);

        return [
            'etapas' => [
                'campo' => count($lotesCampo) > 0 ? [
                    'codigo' => $lotesCampo[0]->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $lotesCampo[0]->fecha_cosecha,
                    'detalles' => [
                        'productor' => $lotesCampo[0]->productor_nombre,
                        'variedad' => $lotesCampo[0]->variedad_nombre
                    ]
                ] : null,
                'planta' => $lotePlanta ? [[
                    'codigo' => $lotePlanta->codigo_lote_planta,
                    'estado' => 'completed',
                    'fecha' => $lotePlanta->fecha_inicio,
                    'detalles' => [
                        'planta' => $lotePlanta->planta_nombre,
                        'rendimiento_pct' => $lotePlanta->rendimiento_pct
                    ]
                ]] : [],
                'salida' => [[
                    'codigo' => $loteSalida->codigo_lote_salida,
                    'estado' => 'completed',
                    'fecha' => $loteSalida->fecha_empaque,
                    'detalles' => [
                        'sku' => $loteSalida->sku,
                        'peso_t' => $loteSalida->peso_t
                    ]
                ]],
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
     * Trazabilidad desde Envío
     */
    private function trazabilidadDesdeEnvio(string $codigo): array
    {
        // 1. Obtener envío
        $envio = DB::table('logistica.envio as e')
            ->select(['e.*'])
            ->where('e.codigo_envio', $codigo)
            ->first();

        if (!$envio) {
            return ['error' => 'Envío no encontrado'];
        }

        // 2. Lotes de salida
        $lotesSalida = DB::select("
            SELECT DISTINCT ls.*, ed.cantidad_t
            FROM logistica.enviodetalle ed
            JOIN planta.lotesalida ls ON ls.lote_salida_id = ed.lote_salida_id
            WHERE ed.envio_id = ?
        ", [$envio->envio_id]);

        // 3. Lotes de planta (del primer lote de salida)
        $lotePlanta = null;
        $lotesCampo = [];
        if (count($lotesSalida) > 0) {
            $lotePlanta = DB::table('planta.loteplanta as lp')
                ->select(['lp.*', 'pl.nombre as planta_nombre'])
                ->leftJoin('cat.planta as pl', 'pl.planta_id', '=', 'lp.planta_id')
                ->where('lp.lote_planta_id', $lotesSalida[0]->lote_planta_id)
                ->first();

            if ($lotePlanta) {
                $lotesCampo = DB::select("
                    SELECT lc.*, p.nombre as productor_nombre, v.nombre_comercial as variedad_nombre
                    FROM planta.loteplanta_entradacampo lpe
                    JOIN campo.lotecampo lc ON lc.lote_campo_id = lpe.lote_campo_id
                    JOIN campo.productor p ON p.productor_id = lc.productor_id
                    JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
                    WHERE lpe.lote_planta_id = ?
                ", [$lotePlanta->lote_planta_id]);
            }
        }

        return [
            'etapas' => [
                'campo' => count($lotesCampo) > 0 ? [
                    'codigo' => $lotesCampo[0]->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $lotesCampo[0]->fecha_cosecha,
                    'detalles' => [
                        'productor' => $lotesCampo[0]->productor_nombre,
                        'variedad' => $lotesCampo[0]->variedad_nombre
                    ]
                ] : null,
                'planta' => $lotePlanta ? [[
                    'codigo' => $lotePlanta->codigo_lote_planta,
                    'estado' => 'completed',
                    'fecha' => $lotePlanta->fecha_inicio,
                    'detalles' => [
                        'planta' => $lotePlanta->planta_nombre
                    ]
                ]] : [],
                'salida' => array_map(function($ls) {
                    return [
                        'codigo' => $ls->codigo_lote_salida,
                        'estado' => 'completed',
                        'fecha' => $ls->fecha_empaque,
                        'detalles' => [
                            'sku' => $ls->sku,
                            'peso_t' => $ls->peso_t,
                            'cantidad_envio_t' => $ls->cantidad_t
                        ]
                    ];
                }, $lotesSalida),
                'envio' => [[
                    'codigo' => $envio->codigo_envio,
                    'estado' => strtolower($envio->estado ?? 'pending'),
                    'fecha' => $envio->fecha_salida,
                    'detalles' => [
                        'estado' => $envio->estado ?? 'PENDIENTE',
                        'transportista' => $envio->transportista_id ? 'Asignado' : 'Sin asignar',
                        'vehiculo' => $envio->vehiculo_id ? 'Asignado' : 'Sin asignar'
                    ]
                ]]

            ]
        ];
    }

    /**
     * Trazabilidad desde Pedido
     */
    private function trazabilidadDesdePedido(string $codigo): array
    {
        // Por ahora devolver mensaje indicando que no está implementado
        // ya que no tenemos la estructura completa de pedido detalle vinculado a lotes
        return [
            'etapas' => [
                'campo' => null,
                'planta' => [],
                'salida' => [],
               'envio' => [],
                'pedido' => [[
                    'codigo' => $codigo,
                    'estado' => 'pending',
                    'fecha' => date('Y-m-d'),
                    'detalles' => [
                        'info' => 'Trazabilidad desde pedido en desarrollo'
                    ]
                ]]
            ]
        ];
    }

    /**
     * Trazabilidad desde Orden de Envío (Planta → Almacén)
     */
    private function trazabilidadDesdeOrdenEnvio(string $codigo): array
    {
        // 1. Obtener orden de envío
        $orden = DB::table('logistica.orden_envio as oe')
            ->select([
                'oe.*',
                'p.nombre as planta_nombre',
                'a.nombre as almacen_nombre',
                'z.nombre as zona_nombre',
                't.nombre as conductor_nombre',
                'v.placa as vehiculo_placa',
                'v.marca as vehiculo_marca'
            ])
            ->leftJoin('cat.planta as p', 'p.planta_id', '=', 'oe.planta_origen_id')
            ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'oe.almacen_destino_id')
            ->leftJoin('almacen.zona as z', 'z.zona_id', '=', 'oe.zona_destino_id')
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'oe.transportista_id')
            ->leftJoin('cat.vehiculo as v', 'v.vehiculo_id', '=', 'oe.vehiculo_id')
            ->where('oe.codigo_orden', $codigo)
            ->first();

        if (!$orden) {
            return ['error' => 'Orden de envío no encontrada'];
        }

        // 2. Obtener lote de salida
        $loteSalida = DB::table('planta.lotesalida as ls')
            ->select(['ls.*'])
            ->where('ls.lote_salida_id', $orden->lote_salida_id)
            ->first();

        // 3. Obtener lote de planta
        $lotePlanta = null;
        $loteCampo = null;
        if ($loteSalida) {
            $lotePlanta = DB::table('planta.loteplanta as lp')
                ->select(['lp.*', 'pl.nombre as planta_nombre'])
                ->leftJoin('cat.planta as pl', 'pl.planta_id', '=', 'lp.planta_id')
                ->where('lp.lote_planta_id', $loteSalida->lote_planta_id)
                ->first();

            // 4. Obtener lotes de campo
            if ($lotePlanta) {
                $loteCampo = DB::table('campo.lotecampo as lc')
                    ->select(['lc.*', 'pr.nombre as productor_nombre', 'v.nombre_comercial as variedad'])
                    ->leftJoin('campo.productor as pr', 'pr.productor_id', '=', 'lc.productor_id')
                    ->leftJoin('cat.variedadpapa as v', 'v.variedad_id', '=', 'lc.variedad_id')
                    ->join('planta.loteplanta_entradacampo as lpe', 'lpe.lote_campo_id', '=', 'lc.lote_campo_id')
                    ->where('lpe.lote_planta_id', $lotePlanta->lote_planta_id)
                    ->first();
            }
        }

        // 5. Verificar si hay recepción en almacén
        $recepcion = DB::table('almacen.recepcion as r')
            ->select(['r.*', 'u.codigo_ubicacion', 'z.nombre as zona_recepcion'])
            ->leftJoin('almacen.ubicacion as u', 'u.ubicacion_id', '=', 'r.ubicacion_id')
            ->leftJoin('almacen.zona as z', 'z.zona_id', '=', 'r.zona_id')
            ->where('r.orden_envio_id', $orden->orden_envio_id)
            ->first();

        return [
            'etapas' => [
                'campo' => $loteCampo ? [
                    'codigo' => $loteCampo->codigo_lote_campo,
                    'estado' => 'completed',
                    'fecha' => $loteCampo->fecha_cosecha,
                    'detalles' => [
                        'productor' => $loteCampo->productor_nombre,
                        'variedad' => $loteCampo->variedad
                    ]
                ] : null,
                'planta' => $lotePlanta ? [[
                    'codigo' => $lotePlanta->codigo_lote_planta,
                    'estado' => 'completed',
                    'fecha' => $lotePlanta->fecha_inicio,
                    'detalles' => [
                        'planta' => $lotePlanta->planta_nombre,
                        'rendimiento' => $lotePlanta->rendimiento_pct . '%'
                    ]
                ]] : [],
                'salida' => $loteSalida ? [[
                    'codigo' => $loteSalida->codigo_lote_salida,
                    'estado' => 'completed',
                    'fecha' => $loteSalida->fecha_empaque,
                    'detalles' => [
                        'sku' => $loteSalida->sku,
                        'peso' => $loteSalida->peso_t . ' t'
                    ]
                ]] : [],
                'orden_envio' => [[
                    'codigo' => $orden->codigo_orden,
                    'estado' => strtolower($orden->estado),
                    'fecha' => $orden->fecha_creacion,
                    'detalles' => [
                        'planta_origen' => $orden->planta_nombre,
                        'almacen_destino' => $orden->almacen_nombre,
                        'conductor' => $orden->conductor_nombre ?? 'Sin asignar',
                        'vehiculo' => $orden->vehiculo_placa ?? 'Sin asignar',
                        'cantidad' => $orden->cantidad_t . ' t',
                        'prioridad' => $orden->prioridad
                    ]
                ]],
                'almacen' => $recepcion ? [[
                    'codigo' => 'REC-' . $recepcion->recepcion_id,
                    'estado' => 'completed',
                    'fecha' => $recepcion->fecha_recepcion,
                    'detalles' => [
                        'zona' => $recepcion->zona_recepcion,
                        'ubicacion' => $recepcion->codigo_ubicacion,
                        'cantidad_recibida' => $recepcion->cantidad_recibida_t . ' t',
                        'estado_producto' => $recepcion->estado_producto ?? 'BUENO'
                    ]
                ]] : []
            ]
        ];
    }

    /**
     * Exportar reporte de trazabilidad a PDF
     */
    public function exportPdf(string $tipo, string $codigo)
    {
        // Obtener datos de trazabilidad directamente (no como JSON)
        $datos = match($tipo) {
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
                    if (isset($etapa[0]['codigo'])) $totalEtapas++;
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

