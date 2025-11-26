<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Departamento;
use App\Models\Cat\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

/**
 * Controlador CRUD de Municipios (cat.municipio)
 */
class MunicipioController extends Controller
{
    /** Listado paginado con filtro por departamento */
    public function index(Request $request): View|JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $departamentoId = (int) $request->get('departamento_id', 0);

        $municipios = Municipio::query()
            ->when($departamentoId > 0, fn($b) => $b->where('departamento_id', $departamentoId))
            ->when($q !== '', fn ($b) => $b->where('nombre', 'ilike', "%{$q}%"))
            ->orderBy('municipio_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q, 'departamento_id' => $departamentoId]);

        $departamentos = Departamento::orderBy('nombre')->get();

        // Si se pide JSON (app móvil), devolvemos listado y metadatos de filtro
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de municipios obtenido correctamente.',
                'data' => $municipios,
                'filters' => [
                    'q' => $q,
                    'departamento_id' => $departamentoId,
                ],
            ]);
        }

        return view('cat.municipios.index', compact('municipios', 'q', 'departamentoId', 'departamentos'));
    }

    /** Form crear */
    public function create(): View
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('cat.municipios.create', compact('departamentos'));
    }

    /** Guardar */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'departamento_id' => ['required', 'integer', Rule::exists('departamento', 'departamento_id')],
            'nombre' => ['required', 'string', 'max:120'],
        ]);

        $municipio = Municipio::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Municipio creado.',
                'data' => $municipio,
            ], 201);
        }

        return redirect()->route('cat.municipios.index')->with('status', 'Municipio creado.');
    }

    /** Form editar */
    public function edit($id): View
    {
        $municipioId = (int) $id;
        $municipio = Municipio::findOrFail($municipioId);
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('cat.municipios.edit', compact('municipio', 'departamentos'));
    }

    /** Actualizar */
    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $municipioId = (int) $id;
        $municipio = Municipio::findOrFail($municipioId);
        $validated = $request->validate([
            'departamento_id' => ['required', 'integer', Rule::exists('departamento', 'departamento_id')],
            'nombre' => ['required', 'string', 'max:120'],
        ]);
        $municipio->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Municipio actualizado.',
                'data' => $municipio->refresh(),
            ]);
        }

        return redirect()->route('cat.municipios.index')->with('status', 'Municipio actualizado.');
    }

    /** Eliminar */
    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $municipioId = (int) $id;
        $municipio = Municipio::findOrFail($municipioId);
        $municipio->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Municipio eliminado.',
            ]);
        }

        return redirect()->route('cat.municipios.index')->with('status', 'Municipio eliminado.');
    }
}


