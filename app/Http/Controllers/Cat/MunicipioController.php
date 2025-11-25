<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Municipio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\MunicipioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $municipios = Municipio::paginate();

        return view('cat.municipio.index', compact('municipios'))
            ->with('i', ($request->input('page', 1) - 1) * $municipios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $municipio = new Municipio();

        return view('cat.municipio.create', compact('municipio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MunicipioRequest $request): RedirectResponse
    {
        Municipio::create($request->validated());

        return Redirect::route('cat.municipios.index')
            ->with('success', 'Municipio created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $municipio = Municipio::find($id);

        return view('cat.municipio.show', compact('municipio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $municipio = Municipio::find($id);

        return view('cat.municipio.edit', compact('municipio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MunicipioRequest $request, Municipio $municipio): RedirectResponse
    {
        $municipio->update($request->validated());

        return Redirect::route('cat.municipios.index')
            ->with('success', 'Municipio updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Municipio::find($id)->delete();

        return Redirect::route('cat.municipios.index')
            ->with('success', 'Municipio deleted successfully');
    }
}
