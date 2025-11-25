<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Variedadpapa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\VariedadpapaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class VariedadpapaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $variedadpapas = Variedadpapa::paginate();

        return view('cat.variedadpapa.index', compact('variedadpapas'))
            ->with('i', ($request->input('page', 1) - 1) * $variedadpapas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $variedadpapa = new Variedadpapa();

        return view('cat.variedadpapa.create', compact('variedadpapa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariedadpapaRequest $request): RedirectResponse
    {
        Variedadpapa::create($request->validated());

        return Redirect::route('cat.variedadpapas.index')
            ->with('success', 'Variedadpapa created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $variedadpapa = Variedadpapa::find($id);

        return view('cat.variedadpapa.show', compact('variedadpapa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $variedadpapa = Variedadpapa::find($id);

        return view('cat.variedadpapa.edit', compact('variedadpapa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariedadpapaRequest $request, Variedadpapa $variedadpapa): RedirectResponse
    {
        $variedadpapa->update($request->validated());

        return Redirect::route('cat.variedadpapas.index')
            ->with('success', 'Variedadpapa updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Variedadpapa::find($id)->delete();

        return Redirect::route('cat.variedadpapas.index')
            ->with('success', 'Variedadpapa deleted successfully');
    }
}
