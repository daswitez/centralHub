<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;
use App\Models\Cat\Almacen;
use App\Models\Cat\Cliente;
use App\Models\Cat\Transportista;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransaccionAlmacenController extends Controller
{
    /**
     * Formulario para despachar a almacén (almacen.sp_despachar_a_almacen).
     */
    public function showDespacharAlmacenForm(): View
    {
        $almacenes = Almacen::orderBy('nombre')->get();
        $transportistas = Transportista::orderBy('nombre')->get();
        $lotesSalida = DB::table('planta.lotesalida')
            ->orderBy('codigo_lote_salida')
            ->get();

        return view('tx.almacen.despachar_almacen', compact('almacenes', 'transportistas', 'lotesSalida'));
    }

    /**
     * Ejecuta almacen.sp_despachar_a_almacen.
     */
    public function despacharAlmacen(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_envio' => ['required', 'string', 'max:40'],
            'transportista_id' => ['required', 'integer', Rule::exists('transportista', 'transportista_id')],
            'almacen_destino_id' => ['required', 'integer', Rule::exists('almacen', 'almacen_id')],
            'fecha_salida' => ['required', 'date'],
            'detalle' => ['required', 'array', 'min:1'],
            'detalle.*.codigo_lote_salida' => ['required', 'string', 'max:50'],
            'detalle.*.cantidad_t' => ['required', 'numeric', 'min:0.001'],
        ]);

        $jsonDetalle = json_encode($validated['detalle'], JSON_THROW_ON_ERROR);

        DB::statement(
            'select almacen.sp_despachar_a_almacen(?, ?, ?, ?, ?::jsonb)',
            [
                $validated['codigo_envio'],
                $validated['transportista_id'],
                $validated['almacen_destino_id'],
                $validated['fecha_salida'],
                $jsonDetalle,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Envío a almacén registrado correctamente.',
                'data' => ['codigo_envio' => $validated['codigo_envio']],
            ]);
        }

        return redirect()
            ->route('tx.almacen.despachar-al-almacen.form')
            ->with('status', 'Envío a almacén registrado correctamente.');
    }

    /**
     * Formulario para recepcionar un envío (almacen.sp_recepcionar_envio).
     */
    public function showRecepcionarEnvioForm(): View
    {
        $almacenes = Almacen::orderBy('nombre')->get();
        
        // Cargar envíos disponibles para recepción (pendientes o en ruta)
        $envios = DB::select("
            SELECT e.envio_id, e.codigo_envio, e.fecha_salida, e.estado,
                   t.nombre as transportista_nombre,
                   r.codigo_ruta, r.descripcion as ruta_descripcion,
                   count(ed.envio_detalle_id) as total_items,
                   sum(ed.cantidad_t) as peso_total
            FROM logistica.envio e
            LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
            LEFT JOIN logistica.ruta r ON r.ruta_id = e.ruta_id
            LEFT JOIN logistica.enviodetalle ed ON ed.envio_id = e.envio_id
            WHERE e.estado IN ('PENDIENTE', 'EN_RUTA')
            GROUP BY e.envio_id, e.codigo_envio, e.fecha_salida, e.estado,
                     t.nombre, r.codigo_ruta, r.descripcion
            ORDER BY e.fecha_salida DESC
        ");

        return view('tx.almacen.recepcionar_envio', compact('almacenes', 'envios'));
    }

    /**
     * Ejecuta almacen.sp_recepcionar_envio.
     */
    public function recepcionarEnvio(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_envio' => ['required', 'string', 'max:40'],
            'almacen_id' => ['required', 'integer', Rule::exists('almacen', 'almacen_id')],
            'observacion' => ['nullable', 'string', 'max:200'],
        ]);

        try {
            DB::statement(
                'select almacen.sp_recepcionar_envio(?, ?, ?)',
                [
                    $validated['codigo_envio'],
                    $validated['almacen_id'],
                    $validated['observacion'] ?? null,
                ]
            );
        } catch (QueryException $ex) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontró el envío especificado.',
                ], 404);
            }

            return back()
                ->withErrors(['codigo_envio' => 'No se encontró el envío especificado.'])
                ->withInput();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Envío recepcionado y stock actualizado correctamente.',
            ]);
        }

        return redirect()
            ->route('tx.almacen.recepcionar-envio.form')
            ->with('status', 'Envío recepcionado y stock actualizado correctamente.');
    }

    /**
     * Formulario para despachar desde almacén a cliente
     * (almacen.sp_despachar_a_cliente).
     */
    public function showDespacharClienteForm(): View
    {
        $almacenes = Almacen::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $transportistas = Transportista::orderBy('nombre')->get();
        $lotesSalida = DB::table('planta.lotesalida')
            ->orderBy('codigo_lote_salida')
            ->get();

        return view('tx.almacen.despachar_cliente', compact('almacenes', 'clientes', 'transportistas', 'lotesSalida'));
    }

    /**
     * Ejecuta almacen.sp_despachar_a_cliente.
     */
    public function despacharCliente(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_envio' => ['required', 'string', 'max:40'],
            'almacen_origen_id' => ['required', 'integer', Rule::exists('almacen', 'almacen_id')],
            'cliente_id' => ['required', 'integer', Rule::exists('cliente', 'cliente_id')],
            'transportista_id' => ['required', 'integer', Rule::exists('transportista', 'transportista_id')],
            'fecha_salida' => ['required', 'date'],
            'detalle' => ['required', 'array', 'min:1'],
            'detalle.*.codigo_lote_salida' => ['required', 'string', 'max:50'],
            'detalle.*.cantidad_t' => ['required', 'numeric', 'min:0.001'],
        ]);

        $jsonDetalle = json_encode($validated['detalle'], JSON_THROW_ON_ERROR);

        DB::statement(
            'select almacen.sp_despachar_a_cliente(?, ?, ?, ?, ?, ?::jsonb)',
            [
                $validated['codigo_envio'],
                $validated['almacen_origen_id'],
                $validated['cliente_id'],
                $validated['transportista_id'],
                $validated['fecha_salida'],
                $jsonDetalle,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Despacho a cliente registrado y stock descontado correctamente.',
                'data' => ['codigo_envio' => $validated['codigo_envio']],
            ]);
        }

        return redirect()
            ->route('tx.almacen.despachar-al-cliente.form')
            ->with('status', 'Despacho a cliente registrado y stock descontado correctamente.');
    }

    /**
     * API: Buscar información completa de un envío por código
     */
    public function buscarEnvio(string $codigo): JsonResponse
    {
        try {
            // Buscar el envío
            $envio = DB::selectOne('
                SELECT e.envio_id, e.codigo_envio, e.fecha_salida, e.fecha_llegada, e.estado,
                       t.nombre as transportista,
                       r.codigo_ruta as ruta,
                       a.codigo_almacen as almacen_origen,
                       p.nombre as planta_nombre
                FROM logistica.envio e
                LEFT JOIN cat.transportista t ON t.transportista_id = e.transportista_id
                LEFT JOIN logistica.ruta r ON r.ruta_id = e.ruta_id
                LEFT JOIN cat.almacen a ON a.almacen_id = e.almacen_origen_id
                LEFT JOIN cat.planta p ON p.planta_id = (
                    SELECT lp.planta_id 
                    FROM logistica.enviodetalle ed
                    JOIN planta.lotesalida ls ON ls.lote_salida_id = ed.lote_salida_id
                    JOIN planta.loteplanta lp ON lp.lote_planta_id = ls.lote_planta_id
                    WHERE ed.envio_id = e.envio_id
                    LIMIT 1
                )
                WHERE e.codigo_envio = ?
            ', [$codigo]);

            if (!$envio) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Envío no encontrado'
                ], 404);
            }

            // Buscar detalles del envío
            $detalles = DB::select('
                SELECT ed.cantidad_t, ls.codigo_lote_salida, ls.sku
                FROM logistica.enviodetalle ed
                JOIN planta.lotesalida ls ON ls.lote_salida_id = ed.lote_salida_id
                WHERE ed.envio_id = ?
            ', [$envio->envio_id]);

            // Calcular total
            $totalTon = array_sum(array_map(fn($d) => (float)$d->cantidad_t, $detalles));

            return response()->json([
                'status' => 'ok',
                'codigo_envio' => $envio->codigo_envio,
                'fecha_salida' => $envio->fecha_salida,
                'fecha_llegada' => $envio->fecha_llegada,
                'estado' => $envio->estado,
                'transportista' => $envio->transportista,
                'ruta' => $envio->ruta,
                'origen' => $envio->planta_nombre ?? $envio->almacen_origen,
                'total_ton' => $totalTon,
                'detalles' => array_map(function($d) {
                    return [
                        'codigo_lote_salida' => $d->codigo_lote_salida,
                        'sku' => $d->sku,
                        'cantidad_t' => (float)$d->cantidad_t
                    ];
                }, $detalles)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al buscar envío: ' . $e->getMessage()
            ], 500);
        }
    }
}



