<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;
use App\Models\Cat\Cliente;
use App\Models\Cat\Municipio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request): View|JsonResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Listado de clientes obtenido correctamente.',
                'data' => $clientes,
                'filters' => [
                    'q' => $q,
                ],
            ]);
        }

        return view('cat.clientes.index', compact('clientes', 'q'));
    }

    public function create(): View
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.clientes.create', compact('municipios'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
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
        $cliente = Cliente::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Cliente creado.',
                'data' => $cliente,
            ], 201);
        }

        return redirect()->route('cat.clientes.index')->with('status', 'Cliente creado.');
    }

    public function edit($id): View
    {
        $clienteId = (int) $id;
        $cliente = Cliente::findOrFail($clienteId);
        $municipios = Municipio::orderBy('nombre')->get();
        return view('cat.clientes.edit', compact('cliente', 'municipios'));
    }

    public function update(Request $request, $id): RedirectResponse|JsonResponse
    {
        $clienteId = (int) $id;
        $cliente = Cliente::findOrFail($clienteId);
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

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Cliente actualizado.',
                'data' => $cliente->refresh(),
            ]);
        }

        return redirect()->route('cat.clientes.index')->with('status', 'Cliente actualizado.');
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
    {
        $clienteId = (int) $id;
        $cliente = Cliente::findOrFail($clienteId);
        $cliente->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Cliente eliminado.',
            ]);
        }

        return redirect()->route('cat.clientes.index')->with('status', 'Cliente eliminado.');
    }
}


