<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Transportista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransportistaController extends Controller
{
    public function index(Request $request): View|JsonResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de transportistas obtenido correctamente.',
                'data' => $items,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('cat.transportistas.index', ['transportistas' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        return view('cat.transportistas.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_transp' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'nro_licencia' => ['nullable', 'string', 'max:60'],
        ]);
        $transportista = Transportista::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Transportista creado.',
                'data' => $transportista,
            ], 201);
        }

        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista creado.');
    }

    public function edit(int $id): View
    {
        $transportista = Transportista::findOrFail($id);
        return view('cat.transportistas.edit', compact('transportista'));
    }

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $transportista = Transportista::findOrFail($id);
        $validated = $request->validate([
            'codigo_transp' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'nro_licencia' => ['nullable', 'string', 'max:60'],
        ]);
        $transportista->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Transportista actualizado.',
                'data' => $transportista->refresh(),
            ]);
        }

        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista actualizado.');
    }

    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $transportista = Transportista::findOrFail($id);
        $transportista->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Transportista eliminado.',
            ]);
        }

        return redirect()->route('cat.transportistas.index')->with('status', 'Transportista eliminado.');
    }
}


