<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Almacen;
use App\Models\Cat\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlmacenController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = Almacen::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_almacen', 'ilike', "%{$q}%")
                    ->orWhere('nombre', 'ilike', "%{$q}%");
            })
            ->orderBy('almacen_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('cat.almacenes.index', ['almacenes' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.almacenes.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_almacen' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', 'exists:cat.municipio,municipio_id'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        Almacen::create($validated);
        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén creado.');
    }

    public function edit(int $id): View
    {
        $almacen = Almacen::findOrFail($id);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.almacenes.edit', compact('almacen', 'municipios'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $almacen = Almacen::findOrFail($id);
        $validated = $request->validate([
            'codigo_almacen' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', 'exists:cat.municipio,municipio_id'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $almacen->update($validated);
        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén actualizado.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->delete();
        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén eliminado.');
    }
}


