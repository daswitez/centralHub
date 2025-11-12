<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\Campo\LoteCampo;
use App\Models\Campo\Productor;
use App\Models\Cat\VariedadPapa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoteCampoController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $items = LoteCampo::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_lote_campo', 'ilike', "%{$q}%");
            })
            ->orderBy('lote_campo_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('campo.lotes.index', ['lotes' => $items, 'q' => $q]);
    }

    public function create(): View
    {
        $productores = Productor::orderBy('nombre')->get();
        $variedades = VariedadPapa::orderBy('nombre_comercial')->get();
        return view('campo.lotes.create', compact('productores', 'variedades'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_lote_campo' => ['required', 'string', 'max:50'],
            'productor_id' => ['required', 'integer', 'exists:campo.productor,productor_id'],
            'variedad_id' => ['required', 'integer', 'exists:cat.variedadpapa,variedad_id'],
            'superficie_ha' => ['required', 'numeric'],
            'fecha_siembra' => ['required', 'date'],
            'fecha_cosecha' => ['nullable', 'date'],
            'humedad_suelo_pct' => ['nullable', 'numeric'],
        ]);
        LoteCampo::create($validated);
        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo creado.');
    }

    public function edit(int $id): View
    {
        $lote = LoteCampo::findOrFail($id);
        $productores = Productor::orderBy('nombre')->get();
        $variedades = VariedadPapa::orderBy('nombre_comercial')->get();
        return view('campo.lotes.edit', compact('lote', 'productores', 'variedades'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $lote = LoteCampo::findOrFail($id);
        $validated = $request->validate([
            'codigo_lote_campo' => ['required', 'string', 'max:50'],
            'productor_id' => ['required', 'integer', 'exists:campo.productor,productor_id'],
            'variedad_id' => ['required', 'integer', 'exists:cat.variedadpapa,variedad_id'],
            'superficie_ha' => ['required', 'numeric'],
            'fecha_siembra' => ['required', 'date'],
            'fecha_cosecha' => ['nullable', 'date'],
            'humedad_suelo_pct' => ['nullable', 'numeric'],
        ]);
        $lote->update($validated);
        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo actualizado.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $lote = LoteCampo::findOrFail($id);
        $lote->delete();
        return redirect()->route('campo.lotes.index')->with('status', 'Lote de campo eliminado.');
    }
}


