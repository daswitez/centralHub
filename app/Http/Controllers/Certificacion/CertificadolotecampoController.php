<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\Certificadolotecampo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadolotecampoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadolotecampoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadolotecampos = Certificadolotecampo::paginate();

        return view('certificacion.certificadolotecampo.index', compact('certificadolotecampos'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadolotecampos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadolotecampo = new Certificadolotecampo();

        return view('certificacion.certificadolotecampo.create', compact('certificadolotecampo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadolotecampoRequest $request): RedirectResponse
    {
        Certificadolotecampo::create($request->validated());

        return Redirect::route('certificacion.certificadolotecampos.index')
            ->with('success', 'Certificadolotecampo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadolotecampo = Certificadolotecampo::find($id);

        return view('certificacion.certificadolotecampo.show', compact('certificadolotecampo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadolotecampo = Certificadolotecampo::find($id);

        return view('certificacion.certificadolotecampo.edit', compact('certificadolotecampo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadolotecampoRequest $request, Certificadolotecampo $certificadolotecampo): RedirectResponse
    {
        $certificadolotecampo->update($request->validated());

        return Redirect::route('certificacion.certificadolotecampos.index')
            ->with('success', 'Certificadolotecampo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Certificadolotecampo::find($id)->delete();

        return Redirect::route('certificacion.certificadolotecampos.index')
            ->with('success', 'Certificadolotecampo deleted successfully');
    }
}
