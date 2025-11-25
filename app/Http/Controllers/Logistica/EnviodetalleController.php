<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;

use App\Models\Logistica\Enviodetalle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Logistica\EnviodetalleRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EnviodetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $enviodetalles = Enviodetalle::paginate();

        return view('logistica.enviodetalle.index', compact('enviodetalles'))
            ->with('i', ($request->input('page', 1) - 1) * $enviodetalles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enviodetalle = new Enviodetalle();

        return view('logistica.enviodetalle.create', compact('enviodetalle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnviodetalleRequest $request): RedirectResponse
    {
        Enviodetalle::create($request->validated());

        return Redirect::route('logistica.enviodetalles.index')
            ->with('success', 'Enviodetalle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $enviodetalle = Enviodetalle::find($id);

        return view('logistica.enviodetalle.show', compact('enviodetalle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $enviodetalle = Enviodetalle::find($id);

        return view('logistica.enviodetalle.edit', compact('enviodetalle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnviodetalleRequest $request, Enviodetalle $enviodetalle): RedirectResponse
    {
        $enviodetalle->update($request->validated());

        return Redirect::route('logistica.enviodetalles.index')
            ->with('success', 'Enviodetalle updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Enviodetalle::find($id)->delete();

        return Redirect::route('logistica.enviodetalles.index')
            ->with('success', 'Enviodetalle deleted successfully');
    }
}
