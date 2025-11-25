<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\CertificadoLotePlanta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadoLotePlantaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadoLotePlantaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadoloteplanta = CertificadoLotePlanta::paginate();

        return view('certificacion.certificadoloteplantum.index', compact('certificadoloteplanta'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadoloteplanta->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadoloteplanta = new CertificadoLotePlanta();

        return view('certificacion.certificadoloteplantum.create', compact('certificacion.certificadoloteplantum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadoLotePlantaRequest $request): RedirectResponse
    {
        CertificadoLotePlanta::create($request->validated());

        return Redirect::route('certificacion.certificadoloteplanta.index')
            ->with('success', 'CertificadoLotePlanta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadoloteplanta = CertificadoLotePlanta::find($id);

        return view('certificacion.certificadoloteplantum.show', compact('certificacion.certificadoloteplantum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadoloteplanta = CertificadoLotePlanta::find($id);

        return view('certificacion.certificadoloteplantum.edit', compact('certificacion.certificadoloteplantum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadoLotePlantaRequest $request, CertificadoLotePlanta $certificadoloteplanta): RedirectResponse
    {
        $certificadoloteplanta->update($request->validated());

        return Redirect::route('certificacion.certificadoloteplanta.index')
            ->with('success', 'CertificadoLotePlanta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CertificadoLotePlanta::find($id)->delete();

        return Redirect::route('certificacion.certificadoloteplanta.index')
            ->with('success', 'CertificadoLotePlanta deleted successfully');
    }
}
