<?php

namespace App\Http\Controllers\Certificacion;

use App\Http\Controllers\Controller;

use App\Models\Certificacion\Certificadocadena;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Certificacion\CertificadocadenaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CertificadocadenaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $certificadocadenas = Certificadocadena::paginate();

        return view('certificacion.certificadocadena.index', compact('certificadocadenas'))
            ->with('i', ($request->input('page', 1) - 1) * $certificadocadenas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $certificadocadena = new Certificadocadena();

        return view('certificacion.certificadocadena.create', compact('certificadocadena'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CertificadocadenaRequest $request): RedirectResponse
    {
        Certificadocadena::create($request->validated());

        return Redirect::route('certificacion.certificadocadenas.index')
            ->with('success', 'Certificadocadena created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $certificadocadena = Certificadocadena::find($id);

        return view('certificacion.certificadocadena.show', compact('certificadocadena'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $certificadocadena = Certificadocadena::find($id);

        return view('certificacion.certificadocadena.edit', compact('certificadocadena'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CertificadocadenaRequest $request, Certificadocadena $certificadocadena): RedirectResponse
    {
        $certificadocadena->update($request->validated());

        return Redirect::route('certificacion.certificadocadenas.index')
            ->with('success', 'Certificadocadena updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Certificadocadena::find($id)->delete();

        return Redirect::route('certificacion.certificadocadenas.index')
            ->with('success', 'Certificadocadena deleted successfully');
    }
}
