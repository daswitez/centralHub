<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;

use App\Models\Planta\LotePlanta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Planta\LotePlantaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LotePlantaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $loteplanta = LotePlanta::paginate();

        return view('planta.loteplantum.index', compact('loteplanta'))
            ->with('i', ($request->input('page', 1) - 1) * $loteplanta->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $loteplanta = new LotePlanta();

        return view('planta.loteplantum.create', compact('planta.loteplantum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LotePlantaRequest $request): RedirectResponse
    {
        LotePlanta::create($request->validated());

        return Redirect::route('planta.loteplanta.index')
            ->with('success', 'LotePlanta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $loteplanta = LotePlanta::find($id);

        return view('planta.loteplantum.show', compact('planta.loteplantum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $loteplanta = LotePlanta::find($id);

        return view('planta.loteplantum.edit', compact('planta.loteplantum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LotePlantaRequest $request, LotePlanta $loteplanta): RedirectResponse
    {
        $loteplanta->update($request->validated());

        return Redirect::route('planta.loteplanta.index')
            ->with('success', 'LotePlanta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        LotePlanta::find($id)->delete();

        return Redirect::route('planta.loteplanta.index')
            ->with('success', 'LotePlanta deleted successfully');
    }
}
