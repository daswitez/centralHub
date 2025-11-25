<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;

use App\Models\Campo\Sensorlectura;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Campo\SensorlecturaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SensorlecturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sensorlecturas = Sensorlectura::paginate();

        return view('campo.sensorlectura.index', compact('sensorlecturas'))
            ->with('i', ($request->input('page', 1) - 1) * $sensorlecturas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sensorlectura = new Sensorlectura();

        return view('campo.sensorlectura.create', compact('sensorlectura'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SensorlecturaRequest $request): RedirectResponse
    {
        Sensorlectura::create($request->validated());

        return Redirect::route('campo.sensorlecturas.index')
            ->with('success', 'Sensorlectura created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $sensorlectura = Sensorlectura::find($id);

        return view('campo.sensorlectura.show', compact('sensorlectura'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $sensorlectura = Sensorlectura::find($id);

        return view('campo.sensorlectura.edit', compact('sensorlectura'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SensorlecturaRequest $request, Sensorlectura $sensorlectura): RedirectResponse
    {
        $sensorlectura->update($request->validated());

        return Redirect::route('campo.sensorlecturas.index')
            ->with('success', 'Sensorlectura updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Sensorlectura::find($id)->delete();

        return Redirect::route('campo.sensorlecturas.index')
            ->with('success', 'Sensorlectura deleted successfully');
    }
}
