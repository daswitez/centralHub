<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\Certificadolotesalida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadolotesalidaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadolotesalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadolotesalidas = Certificadolotesalida::paginate();

        return view('certificacion.certificadolotesalida.index', compact('certificadolotesalidas'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadolotesalidas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadolotesalida = new Certificadolotesalida();

        return view('certificacion.certificadolotesalida.create', compact('certificadolotesalida'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadolotesalidaRequest $request): RedirectResponse
    {
        Certificadolotesalida::create($request->validated());

        return Redirect::route('certificacion.certificadolotesalidas.index')
            ->with('success', 'Certificadolotesalida created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadolotesalida = Certificadolotesalida::find($id);

        return view('certificacion.certificadolotesalida.show', compact('certificadolotesalida'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadolotesalida = Certificadolotesalida::find($id);

        return view('certificacion.certificadolotesalida.edit', compact('certificadolotesalida'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadolotesalidaRequest $request, Certificadolotesalida $certificadolotesalida): RedirectResponse
    {
        $certificadolotesalida->update($request->validated());

        return Redirect::route('certificacion.certificadolotesalidas.index')
            ->with('success', 'Certificadolotesalida updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Certificadolotesalida::find($id)->delete();

        return Redirect::route('certificacion.certificadolotesalidas.index')
            ->with('success', 'Certificadolotesalida deleted successfully');
    }
}
