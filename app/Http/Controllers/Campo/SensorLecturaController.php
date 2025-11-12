<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;
use App\Models\Campo\LoteCampo;
use App\Models\Campo\SensorLectura;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class SensorLecturaController extends Controller
{
    /** Listado con filtros por lote, tipo y rango de fechas */
    public function index(Request $request): View
    {
        $tipo = trim((string) $request->get('tipo', ''));
        $loteId = (int) $request->get('lote_campo_id', 0);
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $lecturas = SensorLectura::query()
            ->when($loteId > 0, fn($b) => $b->where('lote_campo_id', $loteId))
            ->when($tipo !== '', fn($b) => $b->where('tipo', 'ilike', "%{$tipo}%"))
            ->when($desde, fn($b) => $b->where('fecha_hora', '>=', $desde))
            ->when($hasta, fn($b) => $b->where('fecha_hora', '<=', $hasta))
            ->orderByDesc('fecha_hora')
            ->paginate(15)
            ->appends(['tipo' => $tipo, 'lote_campo_id' => $loteId, 'desde' => $desde, 'hasta' => $hasta]);

        $lotes = LoteCampo::orderByDesc('lote_campo_id')->limit(100)->get();

        return view('campo.lecturas.index', compact('lecturas', 'lotes', 'tipo', 'loteId', 'desde', 'hasta'));
    }

    /** Form crear */
    public function create(): View
    {
        $lotes = LoteCampo::orderByDesc('lote_campo_id')->limit(200)->get();
        return view('campo.lecturas.create', compact('lotes'));
    }

    /** Guardar */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lote_campo_id' => ['required', 'integer', Rule::exists('lotecampo', 'lote_campo_id')],
            'fecha_hora' => ['required', 'date'],
            'tipo' => ['required', 'string', 'max:50'],
            'valor_num' => ['nullable', 'numeric'],
            'valor_texto' => ['nullable', 'string', 'max:200'],
        ]);

        SensorLectura::create($validated);
        return redirect()->route('campo.lecturas.index')->with('status', 'Lectura registrada.');
    }

    /** Form editar */
    public function edit(int $id): View
    {
        $lectura = SensorLectura::findOrFail($id);
        $lotes = LoteCampo::orderByDesc('lote_campo_id')->limit(200)->get();
        return view('campo.lecturas.edit', compact('lectura', 'lotes'));
    }

    /** Actualizar */
    public function update(Request $request, int $id): RedirectResponse
    {
        $lectura = SensorLectura::findOrFail($id);
        $validated = $request->validate([
            'lote_campo_id' => ['required', 'integer', Rule::exists('lotecampo', 'lote_campo_id')],
            'fecha_hora' => ['required', 'date'],
            'tipo' => ['required', 'string', 'max:50'],
            'valor_num' => ['nullable', 'numeric'],
            'valor_texto' => ['nullable', 'string', 'max:200'],
        ]);
        $lectura->update($validated);
        return redirect()->route('campo.lecturas.index')->with('status', 'Lectura actualizada.');
    }

    /** Eliminar */
    public function destroy(int $id): RedirectResponse
    {
        $lectura = SensorLectura::findOrFail($id);
        $lectura->delete();
        return redirect()->route('campo.lecturas.index')->with('status', 'Lectura eliminada.');
    }
}


