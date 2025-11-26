<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Municipio;
use App\Models\Cat\Planta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PlantaController extends Controller
{
    public function index(Request $request): View|JsonResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de plantas obtenido correctamente.',
                'data' => $plantas,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('cat.plantas.index', compact('plantas', 'q'));
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.plantas.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_planta' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $planta = Planta::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Planta creada.',
                'data' => $planta,
            ], 201);
        }

        return redirect()->route('cat.plantas.index')->with('status', 'Planta creada.');
    }

    public function edit($id): View
    {
        $plantaId = (int) $id;
        $planta = Planta::findOrFail($plantaId);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.plantas.edit', compact('planta', 'municipios'));
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $plantaId = (int) $id;
        $planta = Planta::findOrFail($plantaId);
        $validated = $request->validate([
            'codigo_planta' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:140'],
            'municipio_id' => ['required', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $planta->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Planta actualizada.',
                'data' => $planta->refresh(),
            ]);
        }

        return redirect()->route('cat.plantas.index')->with('status', 'Planta actualizada.');
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $plantaId = (int) $id;
        $planta = Planta::findOrFail($plantaId);
        $planta->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Planta eliminada.',
            ]);
        }

        return redirect()->route('cat.plantas.index')->with('status', 'Planta eliminada.');
    }
}


