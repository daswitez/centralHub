<?php

namespace App\Http\Controllers\Almacen;

use App\Http\Controllers\Controller;

use App\Models\Almacen\Recepcion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Almacen\RecepcionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $recepcions = Recepcion::paginate();

        return view('almacen.recepcion.index', compact('recepcions'))
            ->with('i', ($request->input('page', 1) - 1) * $recepcions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $recepcion = new Recepcion();

        return view('almacen.recepcion.create', compact('recepcion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecepcionRequest $request): RedirectResponse
    {
        Recepcion::create($request->validated());

        return Redirect::route('almacen.recepcions.index')
            ->with('success', 'Recepcion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $recepcion = Recepcion::find($id);

        return view('almacen.recepcion.show', compact('recepcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $recepcion = Recepcion::find($id);

        return view('almacen.recepcion.edit', compact('recepcion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecepcionRequest $request, Recepcion $recepcion): RedirectResponse
    {
        $recepcion->update($request->validated());

        return Redirect::route('almacen.recepcions.index')
            ->with('success', 'Recepcion updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Recepcion::find($id)->delete();

        return Redirect::route('almacen.recepcions.index')
            ->with('success', 'Recepcion deleted successfully');
    }
}
