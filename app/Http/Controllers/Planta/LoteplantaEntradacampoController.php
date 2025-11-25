<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;

use App\Models\Planta\LoteplantaEntradacampo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Planta\LoteplantaEntradacampoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LoteplantaEntradacampoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $loteplantaEntradacampos = LoteplantaEntradacampo::paginate();

        return view('planta.loteplanta-entradacampo.index', compact('planta.loteplantaEntradacampos'))
            ->with('i', ($request->input('page', 1) - 1) * $loteplantaEntradacampos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $loteplantaEntradacampo = new LoteplantaEntradacampo();

        return view('planta.loteplanta-entradacampo.create', compact('planta.loteplantaEntradacampo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoteplantaEntradacampoRequest $request): RedirectResponse
    {
        LoteplantaEntradacampo::create($request->validated());

        return Redirect::route('planta.loteplanta-entradacampos.index')
            ->with('success', 'LoteplantaEntradacampo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $loteplantaEntradacampo = LoteplantaEntradacampo::find($id);

        return view('planta.loteplanta-entradacampo.show', compact('planta.loteplantaEntradacampo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $loteplantaEntradacampo = LoteplantaEntradacampo::find($id);

        return view('planta.loteplanta-entradacampo.edit', compact('planta.loteplantaEntradacampo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoteplantaEntradacampoRequest $request, LoteplantaEntradacampo $loteplantaEntradacampo): RedirectResponse
    {
        $loteplantaEntradacampo->update($request->validated());

        return Redirect::route('planta.loteplanta-entradacampos.index')
            ->with('success', 'LoteplantaEntradacampo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        LoteplantaEntradacampo::find($id)->delete();

        return Redirect::route('planta.loteplanta-entradacampos.index')
            ->with('success', 'LoteplantaEntradacampo deleted successfully');
    }
}
