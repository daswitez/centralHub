<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\Certificadoenvio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadoenvioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadoenvioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadoenvios = Certificadoenvio::paginate();

        return view('certificacion.certificadoenvio.index', compact('certificadoenvios'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadoenvios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadoenvio = new Certificadoenvio();

        return view('certificacion.certificadoenvio.create', compact('certificadoenvio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadoenvioRequest $request): RedirectResponse
    {
        Certificadoenvio::create($request->validated());

        return Redirect::route('certificacion.certificadoenvios.index')
            ->with('success', 'Certificadoenvio created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadoenvio = Certificadoenvio::find($id);

        return view('certificacion.certificadoenvio.show', compact('certificadoenvio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadoenvio = Certificadoenvio::find($id);

        return view('certificacion.certificadoenvio.edit', compact('certificadoenvio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadoenvioRequest $request, Certificadoenvio $certificadoenvio): RedirectResponse
    {
        $certificadoenvio->update($request->validated());

        return Redirect::route('certificacion.certificadoenvios.index')
            ->with('success', 'Certificadoenvio updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Certificadoenvio::find($id)->delete();

        return Redirect::route('certificacion.certificadoenvios.index')
            ->with('success', 'Certificadoenvio deleted successfully');
    }
}
