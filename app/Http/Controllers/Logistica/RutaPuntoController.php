<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Models\Logistica\Ruta;
use App\Models\Logistica\RutaPunto;
use App\Models\Cat\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RutaPuntoController extends Controller
{
    public function index(Request $request): View
    {
        $rutaId = (int) $request->get('ruta_id', 0);
        $items = RutaPunto::query()
            ->when($rutaId > 0, fn($b) => $b->where('ruta_id', $rutaId))
            ->orderBy('ruta_id')->orderBy('orden')
            ->paginate(20)
            ->appends(['ruta_id' => $rutaId]);
        $rutas = Ruta::orderBy('codigo_ruta')->get();
        return view('logistica.rutapuntos.index', ['puntos' => $items, 'rutas' => $rutas, 'rutaId' => $rutaId]);
    }

    public function create(): View
    {
        return view('logistica.rutapuntos.create', [
            'rutas' => Ruta::orderBy('codigo_ruta')->get(),
            'clientes' => Cliente::orderBy('codigo_cliente')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'integer', Rule::exists('ruta', 'ruta_id')],
            'orden' => ['required', 'integer', 'min:1'],
            'cliente_id' => ['required', 'integer', Rule::exists('cliente', 'cliente_id')],
        ]);
        RutaPunto::create($validated);
        return redirect()->route('logistica.rutapuntos.index', ['ruta_id' => $validated['ruta_id']])->with('status', 'Punto agregado.');
    }

    public function edit(Request $request, int $id): View
    {
        // Para evitar complejidad de composite key, editamos por id autoincrement? No existe.
        // Usamos parámetros ruta_id y orden desde query para encontrar el registro
        $rutaId = (int) $request->get('ruta_id');
        $orden = (int) $request->get('orden');
        $punto = RutaPunto::where('ruta_id', $rutaId)->where('orden', $orden)->firstOrFail();
        return view('logistica.rutapuntos.edit', [
            'punto' => $punto,
            'rutas' => Ruta::orderBy('codigo_ruta')->get(),
            'clientes' => Cliente::orderBy('codigo_cliente')->get(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $rutaId = (int) $request->get('ruta_id_original');
        $ordenOriginal = (int) $request->get('orden_original');
        $punto = RutaPunto::where('ruta_id', $rutaId)->where('orden', $ordenOriginal)->firstOrFail();

        $validated = $request->validate([
            'ruta_id' => ['required', 'integer', Rule::exists('ruta', 'ruta_id')],
            'orden' => ['required', 'integer', 'min:1'],
            'cliente_id' => ['required', 'integer', Rule::exists('cliente', 'cliente_id')],
        ]);

        // Si cambia ruta_id u orden, hay que borrar y crear (por PK compuesta)
        if ($validated['ruta_id'] != $rutaId || $validated['orden'] != $ordenOriginal) {
            $punto->delete();
            RutaPunto::create($validated);
        } else {
            $punto->update($validated);
        }
        return redirect()->route('logistica.rutapuntos.index', ['ruta_id' => $validated['ruta_id']])->with('status', 'Punto actualizado.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $rutaId = (int) $request->get('ruta_id');
        $orden = (int) $request->get('orden');
        $punto = RutaPunto::where('ruta_id', $rutaId)->where('orden', $orden)->firstOrFail();
        $punto->delete();
        return redirect()->route('logistica.rutapuntos.index', ['ruta_id' => $rutaId])->with('status', 'Punto eliminado.');
    }
}


