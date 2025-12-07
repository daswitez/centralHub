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

    /**
     * Ver detalles del almacén con sus zonas y estadísticas
     */
    public function show($id): View
    {
        $almacenId = (int) $id;
        $almacen = Almacen::with('municipio')->findOrFail($almacenId);

        // Zonas del almacén
        $zonas = \DB::table('almacen.zona')
            ->where('almacen_id', $almacenId)
            ->orderBy('codigo_zona')
            ->get();

        // Agregar conteo de ubicaciones por zona
        foreach ($zonas as $zona) {
            $zona->ubicaciones_count = \DB::table('almacen.ubicacion')
                ->where('zona_id', $zona->zona_id)
                ->count();
            
            $zona->ubicaciones_ocupadas = \DB::table('almacen.ubicacion')
                ->where('zona_id', $zona->zona_id)
                ->where('ocupado', true)
                ->count();
            
            $zona->ocupacion_pct = $zona->ubicaciones_count > 0 
                ? round($zona->ubicaciones_ocupadas / $zona->ubicaciones_count * 100, 1) 
                : 0;
        }

        // Estadísticas generales
        $stats = [
            'total_zonas' => count($zonas),
            'total_ubicaciones' => \DB::table('almacen.ubicacion as u')
                ->join('almacen.zona as z', 'z.zona_id', '=', 'u.zona_id')
                ->where('z.almacen_id', $almacenId)
                ->count(),
            'ubicaciones_ocupadas' => \DB::table('almacen.ubicacion as u')
                ->join('almacen.zona as z', 'z.zona_id', '=', 'u.zona_id')
                ->where('z.almacen_id', $almacenId)
                ->where('u.ocupado', true)
                ->count(),
            'ocupacion_pct' => 0
        ];
        
        if ($almacen->capacidad_total_t && $almacen->capacidad_total_t > 0) {
            $ocupado = $almacen->capacidad_total_t - ($almacen->capacidad_disponible_t ?? 0);
            $stats['ocupacion_pct'] = round(($ocupado / $almacen->capacidad_total_t) * 100, 1);
        }

        return view('cat.almacenes.show', compact('almacen', 'zonas', 'stats'));
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



