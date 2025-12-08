<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;


class OrdenEnvioController extends Controller
{
    /**
     * Lista de órdenes de envío
     */
    public function index(Request $request): View
    {
        $estado = $request->get('estado', '');
        
        $query = DB::table('logistica.orden_envio as oe')
            ->select([
                'oe.*',
                'p.nombre as planta_nombre',
                'a.nombre as almacen_nombre',
                'ls.codigo_lote_salida',
                'ls.sku',
                't.nombre as conductor_nombre',
                'v.placa as vehiculo_placa'
            ])
            ->leftJoin('cat.planta as p', 'p.planta_id', '=', 'oe.planta_origen_id')
            ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'oe.almacen_destino_id')
            ->leftJoin('planta.lotesalida as ls', 'ls.lote_salida_id', '=', 'oe.lote_salida_id')
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'oe.transportista_id')
            ->leftJoin('cat.vehiculo as v', 'v.vehiculo_id', '=', 'oe.vehiculo_id');

        if ($estado) {
            $query->where('oe.estado', $estado);
        }

        $ordenes = $query->orderByDesc('oe.fecha_creacion')->paginate(15);

        $estados = ['PENDIENTE', 'CONDUCTOR_ASIGNADO', 'EN_CARGA', 'EN_RUTA', 'ENTREGADO', 'CANCELADO'];

        return view('logistica.ordenes_envio.index', compact('ordenes', 'estados', 'estado'));
    }

    /**
     * Formulario para crear orden de envío
     */
    public function create(): View
    {
        $plantas = DB::table('cat.planta')->select('planta_id', 'nombre', 'codigo_planta')->get();
        $almacenes = DB::table('cat.almacen')->select('almacen_id', 'nombre', 'codigo_almacen')->get();
        
        // Lotes de salida disponibles (no enviados)
        $lotesSalida = DB::table('planta.lotesalida as ls')
            ->select(['ls.*', 'lp.codigo_lote_planta', 'p.nombre as planta_nombre'])
            ->join('planta.loteplanta as lp', 'lp.lote_planta_id', '=', 'ls.lote_planta_id')
            ->join('cat.planta as p', 'p.planta_id', '=', 'lp.planta_id')
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                  ->from('logistica.orden_envio')
                  ->whereColumn('lote_salida_id', 'ls.lote_salida_id')
                  ->whereIn('estado', ['PENDIENTE', 'CONDUCTOR_ASIGNADO', 'EN_CARGA', 'EN_RUTA']);
            })
            ->orderByDesc('ls.fecha_empaque')
            ->get();

        return view('logistica.ordenes_envio.create', compact('plantas', 'almacenes', 'lotesSalida'));
    }

    /**
     * Guardar nueva orden de envío
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'planta_origen_id' => 'required|integer',
            'lote_salida_id' => 'required|integer',
            'almacen_destino_id' => 'required|integer',
            'cantidad_t' => 'required|numeric|min:0.1',
            'fecha_programada' => 'required|date|after_or_equal:today',
            'prioridad' => 'required|in:URGENTE,NORMAL,BAJA',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Generar código único
        $ultimoId = DB::table('logistica.orden_envio')->max('orden_envio_id') ?? 0;
        $codigo = 'OE-' . date('Y') . '-' . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

        $ordenId = DB::table('logistica.orden_envio')->insertGetId([
            'codigo_orden' => $codigo,
            'planta_origen_id' => $validated['planta_origen_id'],
            'lote_salida_id' => $validated['lote_salida_id'],
            'almacen_destino_id' => $validated['almacen_destino_id'],
            'cantidad_t' => $validated['cantidad_t'],
            'fecha_programada' => $validated['fecha_programada'],
            'prioridad' => $validated['prioridad'],
            'observaciones' => $validated['observaciones'],
            'estado' => 'PENDIENTE',
            'creado_por' => auth()->id()
        ], 'orden_envio_id');

        // Intentar asignación automática de conductor y vehículo
        $this->asignarConductorAutomatico($ordenId);

        return redirect()->route('ordenes-envio.index')
            ->with('success', "Orden {$codigo} creada exitosamente");
    }

    /**
     * Ver detalles de la orden
     */
    public function show(int $id): View
    {
        $orden = DB::table('logistica.orden_envio as oe')
            ->select([
                'oe.*',
                'p.nombre as planta_nombre',
                'p.codigo_planta',
                'a.nombre as almacen_nombre',
                'a.codigo_almacen',
                'z.nombre as zona_nombre',
                'ls.codigo_lote_salida',
                'ls.sku',
                'ls.peso_t',
                't.nombre as conductor_nombre',
                't.telefono as conductor_telefono',
                'v.placa as vehiculo_placa',
                'v.marca as vehiculo_marca',
                'v.modelo as vehiculo_modelo'
            ])
            ->leftJoin('cat.planta as p', 'p.planta_id', '=', 'oe.planta_origen_id')
            ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'oe.almacen_destino_id')
            ->leftJoin('almacen.zona as z', 'z.zona_id', '=', 'oe.zona_destino_id')
            ->leftJoin('planta.lotesalida as ls', 'ls.lote_salida_id', '=', 'oe.lote_salida_id')
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'oe.transportista_id')
            ->leftJoin('cat.vehiculo as v', 'v.vehiculo_id', '=', 'oe.vehiculo_id')
            ->where('oe.orden_envio_id', $id)
            ->first();

        if (!$orden) {
            abort(404);
        }

        // Zonas disponibles del almacén destino
        $zonas = DB::table('almacen.zona')
            ->where('almacen_id', $orden->almacen_destino_id)
            ->where('estado', 'DISPONIBLE')
            ->get();

        return view('logistica.ordenes_envio.show', compact('orden', 'zonas'));
    }

    /**
     * Asignar conductor manualmente
     */
    public function asignarConductor(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'transportista_id' => 'required|integer',
            'vehiculo_id' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            // Actualizar orden
            DB::table('logistica.orden_envio')
                ->where('orden_envio_id', $id)
                ->update([
                    'transportista_id' => $request->transportista_id,
                    'vehiculo_id' => $request->vehiculo_id,
                    'estado' => 'CONDUCTOR_ASIGNADO',
                    'fecha_asignacion' => now()
                ]);

            // Cambiar estado del conductor a OCUPADO
            DB::table('cat.transportista')
                ->where('transportista_id', $request->transportista_id)
                ->update(['estado' => 'OCUPADO']);

            // Cambiar estado del vehículo a EN_USO
            DB::table('cat.vehiculo')
                ->where('vehiculo_id', $request->vehiculo_id)
                ->update(['estado' => 'EN_USO']);

            DB::commit();

            return redirect()->route('ordenes-envio.show', $id)
                ->with('success', 'Conductor y vehículo asignados');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asignar: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado de la orden
     */
    public function cambiarEstado(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:EN_CARGA,EN_RUTA,ENTREGADO,CANCELADO'
        ]);

        $updates = ['estado' => $request->estado];

        if ($request->estado === 'EN_RUTA') {
            $updates['fecha_salida'] = now();
        } elseif ($request->estado === 'ENTREGADO') {
            $updates['fecha_llegada'] = now();
            
            // Liberar conductor y vehículo
            $orden = DB::table('logistica.orden_envio')->where('orden_envio_id', $id)->first();
            
            if ($orden->transportista_id) {
                DB::table('cat.transportista')
                    ->where('transportista_id', $orden->transportista_id)
                    ->update(['estado' => 'DISPONIBLE']);
            }
            if ($orden->vehiculo_id) {
                DB::table('cat.vehiculo')
                    ->where('vehiculo_id', $orden->vehiculo_id)
                    ->update(['estado' => 'DISPONIBLE']);
            }
        }

        DB::table('logistica.orden_envio')
            ->where('orden_envio_id', $id)
            ->update($updates);

        return redirect()->route('ordenes-envio.show', $id)
            ->with('success', 'Estado actualizado a ' . $request->estado);
    }

    /**
     * Asignación automática de conductor y vehículo disponibles
     */
    private function asignarConductorAutomatico(int $ordenId): bool
    {
        // Buscar conductor disponible que tenga vehículo asignado
        $conductor = DB::table('cat.transportista as t')
            ->select(['t.transportista_id', 't.vehiculo_asignado_id'])
            ->join('cat.vehiculo as v', 'v.vehiculo_id', '=', 't.vehiculo_asignado_id')
            ->where('t.estado', 'DISPONIBLE')
            ->where('v.estado', 'DISPONIBLE')
            ->first();

        if (!$conductor) {
            return false;
        }

        DB::table('logistica.orden_envio')
            ->where('orden_envio_id', $ordenId)
            ->update([
                'transportista_id' => $conductor->transportista_id,
                'vehiculo_id' => $conductor->vehiculo_asignado_id,
                'estado' => 'CONDUCTOR_ASIGNADO',
                'fecha_asignacion' => now()
            ]);

        DB::table('cat.transportista')
            ->where('transportista_id', $conductor->transportista_id)
            ->update(['estado' => 'OCUPADO']);

        DB::table('cat.vehiculo')
            ->where('vehiculo_id', $conductor->vehiculo_asignado_id)
            ->update(['estado' => 'EN_USO']);

        return true;
    }

    /**
     * Exportar Guía de Remisión a PDF
     */
    public function exportPdf(int $id)
    {
        $orden = DB::table('logistica.orden_envio as oe')
            ->select([
                'oe.*',
                'p.nombre as planta_nombre',
                'p.codigo_planta',
                'p.direccion as planta_direccion',
                'a.nombre as almacen_nombre',
                'a.codigo_almacen',
                'z.nombre as zona_nombre',
                't.nombre as conductor_nombre',
                't.telefono as conductor_telefono',
                'v.placa as vehiculo_placa',
                'v.marca as vehiculo_marca',
                'v.modelo as vehiculo_modelo',
                'v.capacidad_t as vehiculo_capacidad'
            ])
            ->leftJoin('cat.planta as p', 'p.planta_id', '=', 'oe.planta_origen_id')
            ->leftJoin('cat.almacen as a', 'a.almacen_id', '=', 'oe.almacen_destino_id')
            ->leftJoin('almacen.zona as z', 'z.zona_id', '=', 'oe.zona_destino_id')
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'oe.transportista_id')
            ->leftJoin('cat.vehiculo as v', 'v.vehiculo_id', '=', 'oe.vehiculo_id')
            ->where('oe.orden_envio_id', $id)
            ->first();

        if (!$orden) {
            abort(404);
        }

        // Obtener lotes asociados
        $lotes = DB::table('planta.lotesalida')
            ->where('lote_salida_id', $orden->lote_salida_id)
            ->get();

        // Datos para la vista
        $planta = (object)[
            'nombre' => $orden->planta_nombre,
            'codigo_planta' => $orden->codigo_planta,
            'direccion' => $orden->planta_direccion ?? 'N/A'
        ];

        $almacen = (object)[
            'nombre' => $orden->almacen_nombre,
            'codigo_almacen' => $orden->codigo_almacen
        ];

        $zona = $orden->zona_nombre ? (object)['nombre' => $orden->zona_nombre] : null;

        $transportista = (object)[
            'nombre' => $orden->conductor_nombre,
            'telefono' => $orden->conductor_telefono
        ];

        $vehiculo = (object)[
            'placa' => $orden->vehiculo_placa,
            'marca' => $orden->vehiculo_marca,
            'modelo' => $orden->vehiculo_modelo,
            'capacidad_t' => $orden->vehiculo_capacidad
        ];

        $pdf = Pdf::loadView('pdf.orden_envio', compact(
            'orden', 'planta', 'almacen', 'zona', 'transportista', 'vehiculo', 'lotes'
        ));

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("guia_remision_{$orden->codigo_orden}.pdf");
    }
}
