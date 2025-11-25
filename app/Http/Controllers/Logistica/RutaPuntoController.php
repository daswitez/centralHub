<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;

use App\Models\Logistica\Rutapunto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Logistica\RutapuntoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RutapuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $rutapuntos = Rutapunto::paginate();

        return view('logistica.rutapunto.index', compact('rutapuntos'))
            ->with('i', ($request->input('page', 1) - 1) * $rutapuntos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $rutapunto = new Rutapunto();

        return view('logistica.rutapunto.create', compact('rutapunto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RutapuntoRequest $request): RedirectResponse
    {
        Rutapunto::create($request->validated());

        return Redirect::route('logistica.rutapuntos.index')
            ->with('success', 'Rutapunto created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $rutapunto = Rutapunto::find($id);

        return view('logistica.rutapunto.show', compact('rutapunto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $rutapunto = Rutapunto::find($id);

        return view('logistica.rutapunto.edit', compact('rutapunto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RutapuntoRequest $request, Rutapunto $rutapunto): RedirectResponse
    {
        $rutapunto->update($request->validated());

        return Redirect::route('logistica.rutapuntos.index')
            ->with('success', 'Rutapunto updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Rutapunto::find($id)->delete();

        return Redirect::route('logistica.rutapuntos.index')
            ->with('success', 'Rutapunto deleted successfully');
    }
}
