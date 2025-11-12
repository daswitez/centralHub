<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador CRUD de Departamentos (cat.departamento)
 */
class DepartamentoController extends Controller
{
    /** Listado paginado */
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $departamentos = Departamento::query()
            ->when($q !== '', fn ($qBuilder) => $qBuilder->where('nombre', 'ilike', "%{$q}%"))
            ->orderBy('departamento_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('cat.departamentos.index', compact('departamentos', 'q'));
    }

    /** Form crear */
    public function create(): View
    {
        return view('cat.departamentos.create');
    }

    /** Guardar */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
        ]);

        Departamento::create($validated);
        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento creado.');
    }

    /** Form editar */
    public function edit(int $id): View
    {
        $departamento = Departamento::findOrFail($id);
        return view('cat.departamentos.edit', compact('departamento'));
    }

    /** Actualizar */
    public function update(Request $request, int $id): RedirectResponse
    {
        $departamento = Departamento::findOrFail($id);
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
        ]);
        $departamento->update($validated);
        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento actualizado.');
    }

    /** Eliminar */
    public function destroy(int $id): RedirectResponse
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();
        return redirect()->route('cat.departamentos.index')->with('status', 'Departamento eliminado.');
    }
}


