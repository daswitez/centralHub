<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\Campo\Productor;
use App\Models\Cat\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProductorController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = Productor::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_productor', 'ilike', "%{$q}%")
                    ->orWhere('nombre', 'ilike', "%{$q}%");
            })
            ->orderBy('productor_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('campo.productores.index', ['productores' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('campo.productores.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_productor' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'telefono' => ['nullable', 'string', 'max:40'],
        ]);
        Productor::create($validated);
        return redirect()->route('campo.productores.index')->with('status', 'Productor creado.');
    }

    public function edit(int $id): View
    {
        $productor = Productor::findOrFail($id);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('campo.productores.edit', compact('productor', 'municipios'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $productor = Productor::findOrFail($id);
        $validated = $request->validate([
            'codigo_productor' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'telefono' => ['nullable', 'string', 'max:40'],
        ]);
        $productor->update($validated);
        return redirect()->route('campo.productores.index')->with('status', 'Productor actualizado.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $productor = Productor::findOrFail($id);
        $productor->delete();
        return redirect()->route('campo.productores.index')->with('status', 'Productor eliminado.');
    }
}


