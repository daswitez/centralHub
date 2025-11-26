<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\VariedadPapa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador CRUD de Variedades de Papa (cat.variedadpapa)
 */
class VariedadPapaController extends Controller
{
    /** Listado paginado */
    public function index(Request $request): View|JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $variedades = VariedadPapa::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where(function ($q2) use ($q) {
                    $q2->where('codigo_variedad', 'ilike', "%{$q}%")
                        ->orWhere('nombre_comercial', 'ilike', "%{$q}%");
                });
            })
            ->orderBy('variedad_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        // Para app móvil, devolvemos listado paginado en JSON
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de variedades obtenido correctamente.',
                'data' => $variedades,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('cat.variedades.index', compact('variedades', 'q'));
    }

    /** Form crear */
    public function create(): View
    {
        return view('cat.variedades.create');
    }

    /** Guardar */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_variedad' => ['required', 'string', 'max:40'],
            'nombre_comercial' => ['required', 'string', 'max:120'],
            'aptitud' => ['nullable', 'string', 'max:80'],
            'ciclo_dias_min' => ['nullable', 'integer'],
            'ciclo_dias_max' => ['nullable', 'integer'],
        ]);

        $variedad = VariedadPapa::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Variedad creada.',
                'data' => $variedad,
            ], 201);
        }

        return redirect()->route('cat.variedades.index')->with('status', 'Variedad creada.');
    }

    /** Form editar */
    public function edit($id): View
    {
        $variedadId = (int) $id;
        $variedad = VariedadPapa::findOrFail($variedadId);
        return view('cat.variedades.edit', compact('variedad'));
    }

    /** Actualizar */
    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $variedadId = (int) $id;
        $variedad = VariedadPapa::findOrFail($variedadId);
        $validated = $request->validate([
            'codigo_variedad' => ['required', 'string', 'max:40'],
            'nombre_comercial' => ['required', 'string', 'max:120'],
            'aptitud' => ['nullable', 'string', 'max:80'],
            'ciclo_dias_min' => ['nullable', 'integer'],
            'ciclo_dias_max' => ['nullable', 'integer'],
        ]);
        $variedad->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Variedad actualizada.',
                'data' => $variedad->refresh(),
            ]);
        }

        return redirect()->route('cat.variedades.index')->with('status', 'Variedad actualizada.');
    }

    /** Eliminar */
    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $variedadId = (int) $id;
        $variedad = VariedadPapa::findOrFail($variedadId);
        $variedad->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Variedad eliminada.',
            ]);
        }

        return redirect()->route('cat.variedades.index')->with('status', 'Variedad eliminada.');
    }
}


