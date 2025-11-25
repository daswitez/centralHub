<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\PlantaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PlantaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $planta = Planta::paginate();

        return view('cat.plantum.index', compact('planta'))
            ->with('i', ($request->input('page', 1) - 1) * $planta->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $planta = new Planta();

        return view('cat.plantum.create', compact('cat.plantum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlantaRequest $request): RedirectResponse
    {
        Planta::create($request->validated());

        return Redirect::route('cat.planta.index')
            ->with('success', 'Planta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $planta = Planta::find($id);

        return view('cat.plantum.show', compact('cat.plantum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $planta = Planta::find($id);

        return view('cat.plantum.edit', compact('cat.plantum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlantaRequest $request, Planta $planta): RedirectResponse
    {
        $planta->update($request->validated());

        return Redirect::route('cat.planta.index')
            ->with('success', 'Planta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Planta::find($id)->delete();

        return Redirect::route('cat.planta.index')
            ->with('success', 'Planta deleted successfully');
    }
}
