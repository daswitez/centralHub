<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;

use App\Models\Planta\Controlproceso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Planta\ControlprocesoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ControlprocesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $controlprocesos = Controlproceso::paginate();

        return view('planta.controlproceso.index', compact('controlprocesos'))
            ->with('i', ($request->input('page', 1) - 1) * $controlprocesos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $controlproceso = new Controlproceso();

        return view('planta.controlproceso.create', compact('controlproceso'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ControlprocesoRequest $request): RedirectResponse
    {
        Controlproceso::create($request->validated());

        return Redirect::route('planta.controlprocesos.index')
            ->with('success', 'Controlproceso created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $controlproceso = Controlproceso::find($id);

        return view('planta.controlproceso.show', compact('controlproceso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $controlproceso = Controlproceso::find($id);

        return view('planta.controlproceso.edit', compact('controlproceso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ControlprocesoRequest $request, Controlproceso $controlproceso): RedirectResponse
    {
        $controlproceso->update($request->validated());

        return Redirect::route('planta.controlprocesos.index')
            ->with('success', 'Controlproceso updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Controlproceso::find($id)->delete();

        return Redirect::route('planta.controlprocesos.index')
            ->with('success', 'Controlproceso deleted successfully');
    }
}
