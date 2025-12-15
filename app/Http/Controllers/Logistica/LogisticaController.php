<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LogisticaController extends Controller
{
    public function enviosIndex(): View
    {
        return view('logistica.envio.index');
    }
    public function enviosShow(string $id): View
    {
        return view('logistica.envio.show', [
            'envioId' => $id
        ]);
    }

    public function catalogoIndex(): View
    {
        return view('logistica.catalogo.index');
    }

    public function tiposTransporteIndex(): View
    {
        return view('logistica.tipos-transporte.index');
    }

    public function enviosProductoresIndex(): View
    {
        return view('logistica.envios-productores.index');
    }
}
