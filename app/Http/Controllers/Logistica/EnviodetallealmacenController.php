<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;

use App\Models\Logistica\Enviodetallealmacen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Logistica\EnviodetallealmacenRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EnviodetallealmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $enviodetallealmacens = Enviodetallealmacen::paginate();

        return view('logistica.enviodetallealmacen.index', compact('enviodetallealmacens'))
            ->with('i', ($request->input('page', 1) - 1) * $enviodetallealmacens->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enviodetallealmacen = new Enviodetallealmacen();

        return view('logistica.enviodetallealmacen.create', compact('enviodetallealmacen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnviodetallealmacenRequest $request): RedirectResponse
    {
        Enviodetallealmacen::create($request->validated());

        return Redirect::route('logistica.enviodetallealmacens.index')
            ->with('success', 'Enviodetallealmacen created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $enviodetallealmacen = Enviodetallealmacen::find($id);

        return view('logistica.enviodetallealmacen.show', compact('enviodetallealmacen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $enviodetallealmacen = Enviodetallealmacen::find($id);

        return view('logistica.enviodetallealmacen.edit', compact('enviodetallealmacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EnviodetallealmacenRequest $request, Enviodetallealmacen $enviodetallealmacen): RedirectResponse
    {
        $enviodetallealmacen->update($request->validated());

        return Redirect::route('logistica.enviodetallealmacens.index')
            ->with('success', 'Enviodetallealmacen updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Enviodetallealmacen::find($id)->delete();

        return Redirect::route('logistica.enviodetallealmacens.index')
            ->with('success', 'Enviodetallealmacen deleted successfully');
    }
}
