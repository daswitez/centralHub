<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\Campo\LoteCampo;
use App\Models\Campo\Productor;
use App\Models\Cat\VariedadPapa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class LoteCampoController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $items = LoteCampo::query()
            ->with(['productor', 'variedad'])
            ->withCount('lecturas')
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_lote_campo', 'ilike', "%{$q}%");
            })
            ->orderBy('lote_campo_id', 'desc')
            ->paginate(12)
            ->appends(['q' => $q]);

        // Agregar información de trazabilidad (si fue procesado en planta)
        foreach ($items as $lote) {
            $procesado = \DB::selectOne('
                SELECT count(*) as c 
                FROM planta.loteplanta_entradacampo 
                WHERE lote_campo_id = ?
            ', [$lote->lote_campo_id]);
            $lote->procesado_en_planta = ($procesado->c ?? 0) > 0;
            $lote->num_lotes_planta = (int)($procesado->c ?? 0);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de lotes de campo obtenido correctamente.',
                'data' => $items,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('campo.lotes.index', ['lotes' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        $productores = Productor::orderBy('nombre')->get();
        $variedades = VariedadPapa::orderBy('nombre_comercial')->get();
        return view('campo.lotes.create', compact('productores', 'variedades'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'codigo_lote_campo' => ['required', 'string', 'max:50'],
            'productor_id' => ['required', 'integer', Rule::exists('productor', 'productor_id')],
            'variedad_id' => ['required', 'integer', Rule::exists('variedadpapa', 'variedad_id')],
            'superficie_ha' => ['required', 'numeric'],
            'fecha_siembra' => ['required', 'date'],
            'fecha_cosecha' => ['nullable', 'date'],
            'humedad_suelo_pct' => ['nullable', 'numeric'],
        ]);
        $lote = LoteCampo::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Lote de campo creado.',
                'data' => $lote,
            ], 201);
        }

        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo creado.');
    }

    public function edit($id): View
    {
        $loteId = (int) $id;
        $lote = LoteCampo::findOrFail($loteId);
        $productores = Productor::orderBy('nombre')->get();
        $variedades = VariedadPapa::orderBy('nombre_comercial')->get();
        return view('campo.lotes.edit', compact('lote', 'productores', 'variedades'));
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $loteId = (int) $id;
        $lote = LoteCampo::findOrFail($loteId);
        $validated = $request->validate([
            'codigo_lote_campo' => ['required', 'string', 'max:50'],
            'productor_id' => ['required', 'integer', Rule::exists('productor', 'productor_id')],
            'variedad_id' => ['required', 'integer', Rule::exists('variedadpapa', 'variedad_id')],
            'superficie_ha' => ['required', 'numeric'],
            'fecha_siembra' => ['required', 'date'],
            'fecha_cosecha' => ['nullable', 'date'],
            'humedad_suelo_pct' => ['nullable', 'numeric'],
        ]);
        $lote->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Lote de campo actualizado.',
                'data' => $lote->refresh(),
            ]);
        }

        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo actualizado.');
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $loteId = (int) $id;
        $lote = LoteCampo::findOrFail($loteId);
        $lote->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Lote de campo eliminado.',
            ]);
        }

        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo eliminado.');
    }
}


