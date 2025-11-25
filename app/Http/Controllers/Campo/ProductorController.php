<?php

namespace App\Http\Controllers\Campo;

use App\Http\Controllers\Controller;

use App\Models\Campo\Productor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Campo\ProductorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $productors = Productor::paginate();

        return view('campo.productor.index', compact('productors'))
            ->with('i', ($request->input('page', 1) - 1) * $productors->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $productor = new Productor();

        return view('campo.productor.create', compact('productor'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductorRequest $request): RedirectResponse
    {
        Productor::create($request->validated());

        return Redirect::route('campo.productors.index')
            ->with('success', 'Productor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $productor = Productor::find($id);

        return view('campo.productor.show', compact('productor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $productor = Productor::find($id);

        return view('campo.productor.edit', compact('productor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductorRequest $request, Productor $productor): RedirectResponse
    {
        $productor->update($request->validated());

        return Redirect::route('campo.productors.index')
            ->with('success', 'Productor updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Productor::find($id)->delete();

        return Redirect::route('campo.productors.index')
            ->with('success', 'Productor deleted successfully');
    }
}
