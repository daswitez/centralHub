<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\ClienteRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $clientes = Cliente::paginate();

        return view('cat.cliente.index', compact('clientes'))
            ->with('i', ($request->input('page', 1) - 1) * $clientes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cliente = new Cliente();

        return view('cat.cliente.create', compact('cliente'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClienteRequest $request): RedirectResponse
    {
        Cliente::create($request->validated());

        return Redirect::route('cat.clientes.index')
            ->with('success', 'Cliente created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cliente = Cliente::find($id);

        return view('cat.cliente.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cliente = Cliente::find($id);

        return view('cat.cliente.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClienteRequest $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($request->validated());

        return Redirect::route('cat.clientes.index')
            ->with('success', 'Cliente updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Cliente::find($id)->delete();

        return Redirect::route('cat.clientes.index')
            ->with('success', 'Cliente deleted successfully');
    }
}
