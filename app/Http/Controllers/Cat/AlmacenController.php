<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Almacen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\AlmacenRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $almacens = Almacen::paginate();

        return view('cat.almacen.index', compact('almacens'))
            ->with('i', ($request->input('page', 1) - 1) * $almacens->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $almacen = new Almacen();

        return view('cat.almacen.create', compact('almacen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlmacenRequest $request): RedirectResponse
    {
        Almacen::create($request->validated());

        return Redirect::route('cat.almacens.index')
            ->with('success', 'Almacen created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $almacen = Almacen::find($id);

        return view('cat.almacen.show', compact('almacen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $almacen = Almacen::find($id);

        return view('cat.almacen.edit', compact('almacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlmacenRequest $request, Almacen $almacen): RedirectResponse
    {
        $almacen->update($request->validated());

        return Redirect::route('cat.almacens.index')
            ->with('success', 'Almacen updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Almacen::find($id)->delete();

        return Redirect::route('cat.almacens.index')
            ->with('success', 'Almacen deleted successfully');
    }
}
