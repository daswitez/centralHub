<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Municipio;
use App\Models\Cat\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlantaController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $plantas = Planta::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_planta', 'ilike', "%{$q}%")
                    ->orWhere('nombre', 'ilike', "%{$q}%");
            })
            ->orderBy('planta_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('cat.plantas.index', compact('plantas', 'q'));
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.plantas.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_planta' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', 'exists:cat.municipio,municipio_id'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        Planta::create($validated);
        return redirect()->route('cat.plantas.index')->with('status', 'Planta creada.');
    }

    public function edit(int $id): View
    {
        $planta = Planta::findOrFail($id);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.plantas.edit', compact('planta', 'municipios'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $planta = Planta::findOrFail($id);
        $validated = $request->validate([
            'codigo_planta' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', 'exists:cat.municipio,municipio_id'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $planta->update($validated);
        return redirect()->route('cat.plantas.index')->with('status', 'Planta actualizada.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $planta = Planta::findOrFail($id);
        $planta->delete();
        return redirect()->route('cat.plantas.index')->with('status', 'Planta eliminada.');
    }
}


