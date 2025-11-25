<?php

namespace App\Http\Controllers\Comercial;

use App\Http\Controllers\Controller;

use App\Models\Comercial\Pedidodetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Comercial\PedidodetalleRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PedidodetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $pedidodetalles = Pedidodetalle::paginate();

        return view('comercial.pedidodetalle.index', compact('pedidodetalles'))
            ->with('i', ($request->input('page', 1) - 1) * $pedidodetalles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $pedidodetalle = new Pedidodetalle();

        return view('comercial.pedidodetalle.create', compact('pedidodetalle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PedidodetalleRequest $request): RedirectResponse
    {
        Pedidodetalle::create($request->validated());

        return Redirect::route('comercial.pedidodetalles.index')
            ->with('success', 'Pedidodetalle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $pedidodetalle = Pedidodetalle::find($id);

        return view('comercial.pedidodetalle.show', compact('pedidodetalle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $pedidodetalle = Pedidodetalle::find($id);

        return view('comercial.pedidodetalle.edit', compact('pedidodetalle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PedidodetalleRequest $request, Pedidodetalle $pedidodetalle): RedirectResponse
    {
        $pedidodetalle->update($request->validated());

        return Redirect::route('comercial.pedidodetalles.index')
            ->with('success', 'Pedidodetalle updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Pedidodetalle::find($id)->delete();

        return Redirect::route('comercial.pedidodetalles.index')
            ->with('success', 'Pedidodetalle deleted successfully');
    }
}
