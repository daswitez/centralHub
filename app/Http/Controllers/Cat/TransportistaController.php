<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Transportista;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportistaController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = Transportista::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_transp', 'ilike', "%{$q}%")
                    ->orWhere('nombre', 'ilike', "%{$q}%");
            })
            ->orderBy('transportista_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('cat.transportistas.index', ['transportistas' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        return view('cat.transportistas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_transp' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'nro_licencia' => ['nullable', 'string', 'max:60'],
        ]);
        Transportista::create($validated);
        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista creado.');
    }

    public function edit(int $id): View
    {
        $transportista = Transportista::findOrFail($id);
        return view('cat.transportistas.edit', compact('transportista'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $transportista = Transportista::findOrFail($id);
        $validated = $request->validate([
            'codigo_transp' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'nro_licencia' => ['nullable', 'string', 'max:60'],
        ]);
        $transportista->update($validated);
        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista actualizado.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $transportista = Transportista::findOrFail($id);
        $transportista->delete();
        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista eliminado.');
    }
}


