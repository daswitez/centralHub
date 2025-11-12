<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Cliente;
use App\Models\Cat\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->get('q', ''));
        $clientes = Cliente::query()
            ->when($q !== '', function ($b) use ($q) {
                $b->where('codigo_cliente', 'ilike', "%{$q}%")
                    ->orWhere('nombre', 'ilike', "%{$q}%")
                    ->orWhere('tipo', 'ilike', "%{$q}%");
            })
            ->orderBy('cliente_id', 'asc')
            ->paginate(12)
            ->appends(['q' => $q]);

        return view('cat.clientes.index', compact('clientes', 'q'));
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.clientes.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo_cliente' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:160'],
            'tipo' => ['required', 'string', 'max:60'],
            'municipio_id' => ['nullable', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        Cliente::create($validated);
        return redirect()->route('cat.clientes.index')->with('status', 'Cliente creado.');
    }

    public function edit(int $id): View
    {
        $cliente = Cliente::findOrFail($id);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.clientes.edit', compact('cliente', 'municipios'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cliente = Cliente::findOrFail($id);
        $validated = $request->validate([
            'codigo_cliente' => ['required', 'string', 'max:40'],
            'nombre' => ['required', 'string', 'max:160'],
            'tipo' => ['required', 'string', 'max:60'],
            'municipio_id' => ['nullable', 'integer', Rule::exists('municipio', 'municipio_id')],
            'direccion' => ['nullable', 'string', 'max:200'],
            'lat' => ['nullable', 'numeric'],
            'lon' => ['nullable', 'numeric'],
        ]);
        $cliente->update($validated);
        return redirect()->route('cat.clientes.index')->with('status', 'Cliente actualizado.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect()->route('cat.clientes.index')->with('status', 'Cliente eliminado.');
    }
}


