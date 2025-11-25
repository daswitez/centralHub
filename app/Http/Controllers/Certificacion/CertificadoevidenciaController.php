<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\CertificadoEvidencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadoEvidenciaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadoEvidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadoevidencia = CertificadoEvidencia::paginate();

        return view('certificacion.certificadoevidencium.index', compact('certificadoevidencia'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadoevidencia->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadoevidencia = new CertificadoEvidencia();

        return view('certificacion.certificadoevidencium.create', compact('certificacion.certificadoevidencium'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadoEvidenciaRequest $request): RedirectResponse
    {
        CertificadoEvidencia::create($request->validated());

        return Redirect::route('certificacion.certificadoevidencia.index')
            ->with('success', 'CertificadoEvidencia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadoevidencia = CertificadoEvidencia::find($id);

        return view('certificacion.certificadoevidencium.show', compact('certificacion.certificadoevidencium'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadoevidencia = CertificadoEvidencia::find($id);

        return view('certificacion.certificadoevidencium.edit', compact('certificacion.certificadoevidencium'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadoEvidenciaRequest $request, CertificadoEvidencia $certificadoevidencia): RedirectResponse
    {
        $certificadoevidencia->update($request->validated());

        return Redirect::route('certificacion.certificadoevidencia.index')
            ->with('success', 'CertificadoEvidencia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CertificadoEvidencia::find($id)->delete();

        return Redirect::route('certificacion.certificadoevidencia.index')
            ->with('success', 'CertificadoEvidencia deleted successfully');
    }
}
