<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;

use App\Models\Planta\Lotesalida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Planta\LotesalidaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LotesalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $lotesalidas = Lotesalida::paginate();

        return view('planta.lotesalida.index', compact('lotesalidas'))
            ->with('i', ($request->input('page', 1) - 1) * $lotesalidas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lotesalida = new Lotesalida();

        return view('planta.lotesalida.create', compact('lotesalida'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LotesalidaRequest $request): RedirectResponse
    {
        Lotesalida::create($request->validated());

        return Redirect::route('planta.lotesalidas.index')
            ->with('success', 'Lotesalida created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $lotesalida = Lotesalida::find($id);

        return view('planta.lotesalida.show', compact('lotesalida'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $lotesalida = Lotesalida::find($id);

        return view('planta.lotesalida.edit', compact('lotesalida'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LotesalidaRequest $request, Lotesalida $lotesalida): RedirectResponse
    {
        $lotesalida->update($request->validated());

        return Redirect::route('planta.lotesalidas.index')
            ->with('success', 'Lotesalida updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Lotesalida::find($id)->delete();

        return Redirect::route('planta.lotesalidas.index')
            ->with('success', 'Lotesalida deleted successfully');
    }
}
