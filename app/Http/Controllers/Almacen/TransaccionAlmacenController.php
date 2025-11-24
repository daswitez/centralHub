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

        return view('tx.almacen.recepcionar_envio', compact('almacenes'));
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
}


