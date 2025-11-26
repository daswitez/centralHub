<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador CRUD de Departamentos (cat.departamento)
 */
class DepartamentoController extends Controller
{
    /** Listado paginado */
    public function index(Request $request): View|JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $departamentos = Departamento::query()
            ->when($q !== '', fn ($qBuilder) => $qBuilder->where('nombre', 'ilike', "%{$q}%"))
            ->orderBy('departamento_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        // Si la petición espera JSON (app móvil), devolvemos el listado paginado en JSON
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de departamentos obtenido correctamente.',
                'data' => $departamentos,
            ]);
        }

        return view('cat.departamentos.index', compact('departamentos', 'q'));
    }

    /** Form crear */
    public function create(): View
    {
        return view('cat.departamentos.create');
    }

    /** Guardar */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
        ]);

        $departamento = Departamento::create($validated);

        // Si viene desde app móvil, devolvemos el recurso creado en JSON
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Departamento creado.',
                'data' => $departamento,
            ], 201);
        }

        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento creado.');
    }

    /** Form editar */
    public function edit(int $id): View
    {
        $departamento = Departamento::findOrFail($id);
        return view('cat.departamentos.edit', compact('departamento'));
    }

    /** Actualizar */
    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $departamento = Departamento::findOrFail($id);
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
        ]);
        $departamento->update($validated);

        // Si la petición es JSON, devolvemos el departamento actualizado
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Departamento actualizado.',
                'data' => $departamento->refresh(),
            ]);
        }

        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento actualizado.');
    }

    /** Eliminar */
    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        // Para app móvil devolvemos confirmación en JSON
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Departamento eliminado.',
            ]);
        }

        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento eliminado.');
    }
}


