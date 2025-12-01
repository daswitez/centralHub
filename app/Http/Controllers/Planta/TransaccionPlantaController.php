<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;
use App\Models\Cat\Planta as PlantaCat;
use App\Models\Campo\LoteCampo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransaccionPlantaController extends Controller
{
    /**
     * Muestra el formulario web para registrar un lote de planta
     * usando la función planta.sp_registrar_lote_planta.
     */
    public function showLotePlantaForm(): View
    {
        $plantas = PlantaCat::orderBy('nombre')->get();
        
        // Obtener lotes de campo con información detallada
        $lotesCampo = DB::select('
            SELECT lc.lote_campo_id, lc.codigo_lote_campo, lc.superficie_ha,
                   pr.nombre as productor_nombre,
                   v.nombre_comercial as variedad_nombre, v.codigo_variedad,
                   coalesce(sum(lpe.peso_entrada_t), 0) as peso_usado_t
            FROM campo.lotecampo lc
            LEFT JOIN campo.productor pr ON pr.productor_id = lc.productor_id
            LEFT JOIN cat.variedadpapa v ON v.variedad_id = lc.variedad_id
            LEFT JOIN planta.loteplanta_entradacampo lpe ON lpe.lote_campo_id = lc.lote_campo_id
            GROUP BY lc.lote_campo_id, lc.codigo_lote_campo, lc.superficie_ha,
                     pr.nombre, v.nombre_comercial, v.codigo_variedad
            ORDER BY lc.codigo_lote_campo DESC
        ');
        
        // Calcular rendimiento promedio histórico de cada planta
        $rendimientosPlanta = DB::select('
            SELECT planta_id, 
                   round(avg(rendimiento_pct), 1) as rendimiento_promedio,
                   count(*) as num_lotes
            FROM planta.loteplanta
            WHERE rendimiento_pct IS NOT NULL
            GROUP BY planta_id
        ');
        
        $rendimientos = [];
        foreach ($rendimientosPlanta as $r) {
            $rendimientos[$r->planta_id] = [
                'promedio' => $r->rendimiento_promedio,
                'num_lotes' => $r->num_lotes
            ];
        }

        return view('tx.planta.lote_planta', compact('plantas', 'lotesCampo', 'rendimientos'));
    }

    /**
     * Ejecuta planta.sp_registrar_lote_planta desde formulario web.
     */
    public function registrarLotePlanta(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_lote_planta' => ['required', 'string', 'max:50'],
            'planta_id' => ['required', 'integer', Rule::exists('planta', 'planta_id')], // cat.planta vía search_path
            'fecha_inicio' => ['required', 'date'],
            'entradas' => ['required', 'array', 'min:1'],
            'entradas.*.lote_campo_id' => ['required', 'integer', Rule::exists('lotecampo', 'lote_campo_id')],
            'entradas.*.peso_entrada_t' => ['required', 'numeric', 'min:0.001'],
        ]);

        $jsonEntradas = json_encode($validated['entradas'], JSON_THROW_ON_ERROR);

        DB::statement(
            'select planta.sp_registrar_lote_planta(?, ?, ?, ?::jsonb)',
            [
                $validated['codigo_lote_planta'],
                $validated['planta_id'],
                $validated['fecha_inicio'],
                $jsonEntradas,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Lote de planta registrado correctamente.',
                'data' => [
                    'codigo_lote_planta' => $validated['codigo_lote_planta'],
                    'planta_id' => $validated['planta_id'],
                    'entradas_count' => count($validated['entradas']),
                ],
            ]);
        }

        return redirect()
            ->route('tx.planta.lote-planta.form')
            ->with('status', 'Lote de planta registrado correctamente.');
    }

    /**
     * Muestra el formulario web para registrar lote de salida
     * y opcionalmente crear un envío con planta.sp_registrar_lote_salida_y_envio.
     */
    public function showLoteSalidaEnvioForm(): View
    {
        $lotesPlanta = DB::table('planta.loteplanta')
            ->orderByDesc('lote_planta_id')
            ->get();
        $rutas = DB::table('logistica.ruta')->orderBy('codigo_ruta')->get();
        $transportistas = DB::table('cat.transportista')->orderBy('nombre')->get();

        return view('tx.planta.lote_salida_envio', compact('lotesPlanta', 'rutas', 'transportistas'));
    }

    /**
     * Ejecuta planta.sp_registrar_lote_salida_y_envio desde formulario web o JSON.
     */
    public function registrarLoteSalidaEnvio(Request $request): RedirectResponse|JsonResponse
    {
        $crearEnvio = (bool) $request->boolean('crear_envio', false);

        $rules = [
            'codigo_lote_salida' => ['required', 'string', 'max:50'],
            'lote_planta_id' => ['required', 'integer', Rule::exists('loteplanta', 'lote_planta_id')],
            'sku' => ['required', 'string', 'max:120'],
            'peso_t' => ['required', 'numeric', 'min:0.001'],
            'fecha_empaque' => ['required', 'date'],
            'crear_envio' => ['nullable', 'boolean'],
        ];

        if ($crearEnvio) {
            $rules = array_merge($rules, [
                'codigo_envio' => ['required', 'string', 'max:40'],
                'ruta_id' => ['nullable', 'integer', Rule::exists('ruta', 'ruta_id')],
                'transportista_id' => ['nullable', 'integer', Rule::exists('transportista', 'transportista_id')],
                'fecha_salida' => ['nullable', 'date'],
            ]);
        }

        $validated = $request->validate($rules);

        DB::statement(
            'select planta.sp_registrar_lote_salida_y_envio(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $validated['codigo_lote_salida'],
                $validated['lote_planta_id'],
                $validated['sku'],
                $validated['peso_t'],
                $validated['fecha_empaque'],
                $crearEnvio,
                $validated['codigo_envio'] ?? null,
                $validated['ruta_id'] ?? null,
                $validated['transportista_id'] ?? null,
                $validated['fecha_salida'] ?? null,
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => $crearEnvio
                    ? 'Lote de salida y envío registrados correctamente.'
                    : 'Lote de salida registrado correctamente.',
                'data' => [
                    'codigo_lote_salida' => $validated['codigo_lote_salida'],
                    'codigo_envio' => $validated['codigo_envio'] ?? null,
                    'crear_envio' => $crearEnvio,
                ],
            ]);
        }

        return redirect()
            ->route('tx.planta.lote-salida-envio.form')
            ->with(
                'status',
                $crearEnvio
                    ? 'Lote de salida y envío registrados correctamente.'
                    : 'Lote de salida registrado correctamente.'
            );
    }
}


