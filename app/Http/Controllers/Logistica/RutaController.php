<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Ruta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RutaController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = Ruta::query()
            ->when($q !== '', fn($b) => $b->where('codigo_ruta', 'ilike', "%{$q}%")->orWhere('descripcion', 'ilike', "%{$q}%"))
            ->orderByDesc('ruta_id')
            ->paginate(12)
            ->appends(['q' => $q]);
        return view('logistica.rutas.index', ['rutas' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        return view('logistica.rutas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_ruta' => ['required', 'string', 'max:40'],
            'descripcion' => ['nullable', 'string', 'max:160'],
        ]);
        Ruta::create($validated);
        return redirect()->route('logistica.rutas.index')->with('status', 'Ruta creada.');
    }

    public function edit(int $id): View
    {
        $ruta = Ruta::findOrFail($id);
        return view('logistica.rutas.edit', compact('ruta'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $ruta = Ruta::findOrFail($id);
        $validated = $request->validate([
            'codigo_ruta' => ['required', 'string', 'max:40'],
            'descripcion' => ['nullable', 'string', 'max:160'],
        ]);
        $ruta->update($validated);
        return redirect()->route('logistica.rutas.index')->with('status', 'Ruta actualizada.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();
        return redirect()->route('logistica.rutas.index')->with('status', 'Ruta eliminada.');
    }
}


