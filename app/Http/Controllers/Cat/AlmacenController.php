<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Almacen;
use App\Models\Cat\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class AlmacenController extends Controller
{
    public function index(Request $request): View|JsonResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de almacenes obtenido correctamente.',
                'data' => $items,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('cat.almacenes.index', ['almacenes' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.almacenes.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_almacen' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $almacen = Almacen::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Almacén creado.',
                'data' => $almacen,
            ], 201);
        }

        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén creado.');
    }

    public function edit($id): View
    {
        $almacenId = (int) $id;
        $almacen = Almacen::findOrFail($almacenId);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.almacenes.edit', compact('almacen', 'municipios'));
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $almacenId = (int) $id;
        $almacen = Almacen::findOrFail($almacenId);
        $validated = $request->validate([
            'codigo_almacen' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $almacen->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Almacén actualizado.',
                'data' => $almacen->refresh(),
            ]);
        }

        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén actualizado.');
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $almacenId = (int) $id;
        $almacen = Almacen::findOrFail($almacenId);
        $almacen->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Almacén eliminado.',
            ]);
        }

        return redirect()->route('cat.almacenes.index')->with('status', 'Almacén eliminado.');
    }
}


