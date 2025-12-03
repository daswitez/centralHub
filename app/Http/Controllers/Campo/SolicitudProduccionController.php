<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Exception;

class SolicitudProduccionController extends Controller
{
    /**
     * Listado de solicitudes creadas por planta
     */
    public function index(): View
    {
        $solicitudes = DB::select("
            SELECT s.solicitud_id, s.codigo_solicitud, s.cantidad_solicitada_t,
                   s.fecha_necesaria, s.fecha_solicitud, s.estado,
                   p.nombre as productor_nombre,
                   v.nombre_comercial as variedad_nombre,
                   pl.nombre as planta_nombre,
                   CASE 
                       WHEN ac.asignacion_id IS NOT NULL THEN t.nombre
                       ELSE NULL
                   END as conductor_asignado
            FROM campo.solicitud_produccion s
            LEFT JOIN campo.productor p ON p.productor_id = s.productor_id
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = s.variedad_id
            LEFT JOIN cat.planta pl ON pl.planta_id = s.planta_id
            LEFT JOIN campo.asignacion_conductor ac ON ac.solicitud_id = s.solicitud_id
            LEFT JOIN cat.transportista t ON t.transportista_id = ac.transportista_id
            ORDER BY s.fecha_solicitud DESC
        ");

        return view('campo.solicitudes.index', compact('solicitudes'));
    }

    /**
     * Formulario para crear nueva solicitud
     */
    public function create(): View
    {
        $productores = DB::table('campo.productor')
            ->orderBy('nombre')
            ->get();

        $variedades = DB::table('cat.variedadpapa')
            ->orderBy('nombre_comercial')
            ->get();

        $plantas = DB::table('cat.planta')
            ->orderBy('nombre')
            ->get();

        return view('campo.solicitudes.create', compact('productores', 'variedades', 'plantas'));
    }

    /**
     * Guardar nueva solicitud
     */
public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'planta_id' => 'required|integer',
            'productor_id' => 'required|integer',
            'variedad_id' => 'required|integer',
            'cantidad_solicitada_t' => 'required|numeric|min:0.01',
            'fecha_necesaria' => 'required|date|after:today',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Validar manualmente las foreign keys para esquemas PostgreSQL
        $plantaExists = DB::table('cat.planta')->where('planta_id', $validated['planta_id'])->exists();
        $productorExists = DB::table('campo.productor')->where('productor_id', $validated['productor_id'])->exists();
        $variedadExists = DB::table('cat.variedadpapa')->where('variedad_id', $validated['variedad_id'])->exists();

        if (!$plantaExists || !$productorExists || !$variedadExists) {
            return back()->withInput()->withErrors(['error' => 'Uno o más valores seleccionados no son válidos']);
        }

        try {
            // Generar código de solicitud
            $ultimaSolicitud = DB::table('campo.solicitud_produccion')
                ->orderBy('solicitud_id', 'desc')
                ->first();

            $numero = $ultimaSolicitud ? ((int) substr($ultimaSolicitud->codigo_solicitud ?? '', -3)) + 1 : 1;
            $codigoSolicitud = 'SOL-' . date('Y') . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT);

            DB::table('campo.solicitud_produccion')->insert([
                'codigo_solicitud' => $codigoSolicitud,
                'planta_id' => $validated['planta_id'],
                'productor_id' => $validated['productor_id'],
                'variedad_id' => $validated['variedad_id'],
                'cantidad_solicitada_t' => $validated['cantidad_solicitada_t'],
                'fecha_necesaria' => $validated['fecha_necesaria'],
                'observaciones' => $validated['observaciones'],
                'estado' => 'PENDIENTE',
                'fecha_solicitud' => now()
            ]);

            return redirect()
                ->route('solicitudes.index')
                ->with('success', "Solicitud $codigoSolicitud creada correctamente");

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear solicitud: ' . $e->getMessage()]);
        }
    }

    /**
     * Listado de solicitudes recibidas por productor
     */
    public function misSolicitudes(): View
    {
        $solicitudes = DB::select("
            SELECT s.solicitud_id, s.codigo_solicitud, s.cantidad_solicitada_t,
                   s.fecha_necesaria, s.fecha_solicitud, s.fecha_respuesta, s.estado,
                   s.observaciones, s.justificacion_rechazo,
                   pl.nombre as planta_nombre,
                   v.nombre_comercial as variedad_nombre,
                   CASE 
                       WHEN ac.asignacion_id IS NOT NULL THEN t.nombre
                       ELSE NULL
                   END as conductor_asignado
            FROM campo.solicitud_produccion s
            LEFT JOIN cat.planta pl ON pl.planta_id = s.planta_id
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = s.variedad_id
            LEFT JOIN campo.asignacion_conductor ac ON ac.solicitud_id = s.solicitud_id
            LEFT JOIN cat.transportista t ON t.transportista_id = ac.transportista_id
            ORDER BY 
                CASE WHEN s.estado = 'PENDIENTE' THEN 1 ELSE 2 END,
                s.fecha_necesaria ASC
        ");

        return view('campo.solicitudes.mis_solicitudes', compact('solicitudes'));
    }

    /**
     * Ver detalle de solicitud
     */
    public function show($id): View
    {
        $solicitud = DB::table('campo.solicitud_produccion as s')
            ->select([
                's.*',
                'pl.nombre as planta_nombre',
                'pl.codigo_planta',
                'p.nombre as productor_nombre',
                'p.codigo_productor',
                'v.nombre_comercial as variedad_nombre',
                'v.codigo_variedad',
                't.nombre as conductor_nombre',
                't.telefono as conductor_telefono',
                'ac.estado as estado_asignacion',
                'ac.fecha_asignacion',
                'ac.fecha_inicio_ruta',
                'ac.fecha_completado'
            ])
            ->leftJoin('cat.planta as pl', 'pl.planta_id', '=', 's.planta_id')
            ->leftJoin('campo.productor as p', 'p.productor_id', '=', 's.productor_id')
            ->leftJoin('cat.variedadpapa as v', 'v.variedad_id', '=', 's.variedad_id')
            ->leftJoin('campo.asignacion_conductor as ac', 'ac.solicitud_id', '=', 's.solicitud_id')
            ->leftJoin('cat.transportista as t', 't.transportista_id', '=', 'ac.transportista_id')
            ->where('s.solicitud_id', $id)
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        return view('campo.solicitudes.show', compact('solicitud'));
    }

    /**
     * Responder a solicitud (Aceptar/Rechazar)
     * Si acepta: asigna conductor automáticamente
     */
    public function responder(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:ACEPTAR,RECHAZAR',
            'justificacion_rechazo' => 'required_if:decision,RECHAZAR|nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $solicitud = DB::table('campo.solicitud_produccion')
                ->where('solicitud_id', $id)
                ->first();

            if (!$solicitud) {
                throw new Exception('Solicitud no encontrada');
            }

            if ($solicitud->estado !== 'PENDIENTE') {
                throw new Exception('Esta solicitud ya fue procesada');
            }

            if ($validated['decision'] === 'ACEPTAR') {
                // Buscar conductor disponible
                $conductor = DB::table('cat.transportista')
                    ->where('estado', 'DISPONIBLE')
                    ->orderBy('nombre')
                    ->first();

                if (!$conductor) {
                    throw new Exception('No hay conductores disponibles. Por favor, intente más tarde.');
                }

                // Actualizar solicitud
                DB::table('campo.solicitud_produccion')
                    ->where('solicitud_id', $id)
                    ->update([
                        'estado' => 'ACEPTADA',
                        'fecha_respuesta' => now()
                    ]);

                // Crear asignación
                DB::table('campo.asignacion_conductor')->insert([
                    'solicitud_id' => $id,
                    'transportista_id' => $conductor->transportista_id,
                    'fecha_asignacion' => now(),
                    'estado' => 'ASIGNADO'
                ]);

                // Actualizar estado del conductor
                DB::table('cat.transportista')
                    ->where('transportista_id', $conductor->transportista_id)
                    ->update(['estado' => 'OCUPADO']);

                DB::commit();

                return redirect()
                    ->route('solicitudes.show', $id)
                    ->with('success', "Solicitud aceptada. Conductor asignado: {$conductor->nombre}");

            } else {
                // Rechazar solicitud
                DB::table('campo.solicitud_produccion')
                    ->where('solicitud_id', $id)
                    ->update([
                        'estado' => 'RECHAZADA',
                        'fecha_respuesta' => now(),
                        'justificacion_rechazo' => $validated['justificacion_rechazo']
                    ]);

                DB::commit();

                return redirect()
                    ->route('solicitudes.mis-solicitudes')
                    ->with('success', 'Solicitud rechazada correctamente');
            }

        } catch (Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
