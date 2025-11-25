<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;

use App\Models\Campo\Lotecampo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Campo\LotecampoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LotecampoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $lotecampos = Lotecampo::paginate();

        return view('campo.lotecampo.index', compact('lotecampos'))
            ->with('i', ($request->input('page', 1) - 1) * $lotecampos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $lotecampo = new Lotecampo();

        return view('campo.lotecampo.create', compact('lotecampo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LotecampoRequest $request): RedirectResponse
    {
        Lotecampo::create($request->validated());

        return Redirect::route('campo.lotecampos.index')
            ->with('success', 'Lotecampo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $lotecampo = Lotecampo::find($id);

        return view('campo.lotecampo.show', compact('lotecampo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $lotecampo = Lotecampo::find($id);

        return view('campo.lotecampo.edit', compact('lotecampo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LotecampoRequest $request, Lotecampo $lotecampo): RedirectResponse
    {
        $lotecampo->update($request->validated());

        return Redirect::route('campo.lotecampos.index')
            ->with('success', 'Lotecampo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Lotecampo::find($id)->delete();

        return Redirect::route('campo.lotecampos.index')
            ->with('success', 'Lotecampo deleted successfully');
    }
}
