<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReportesIndexController extends Controller
{
    /**
     * Mostrar el índice de reportes disponibles
     */
    public function index(): View
    {
        return view('reportes.index');
    }
}
