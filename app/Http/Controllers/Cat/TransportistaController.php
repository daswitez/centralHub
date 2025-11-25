<?php

namespace App\Http\Controllers\Cat;

use App\Http\Controllers\Controller;

use App\Models\Cat\Transportista;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Cat\TransportistaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TransportistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $transportista = Transportista::paginate();

        return view('cat.transportistum.index', compact('transportista'))
            ->with('i', ($request->input('page', 1) - 1) * $transportista->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $transportista = new Transportista();

        return view('cat.transportistum.create', compact('cat.transportistum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransportistaRequest $request): RedirectResponse
    {
        Transportista::create($request->validated());

        return Redirect::route('cat.transportista.index')
            ->with('success', 'Transportista created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $transportista = Transportista::find($id);

        return view('cat.transportistum.show', compact('cat.transportistum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $transportista = Transportista::find($id);

        return view('cat.transportistum.edit', compact('cat.transportistum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransportistaRequest $request, Transportista $transportista): RedirectResponse
    {
        $transportista->update($request->validated());

        return Redirect::route('cat.transportista.index')
            ->with('success', 'Transportista updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Transportista::find($id)->delete();

        return Redirect::route('cat.transportista.index')
            ->with('success', 'Transportista deleted successfully');
    }
}
