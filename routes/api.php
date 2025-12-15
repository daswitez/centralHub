<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Gateway Routes
|--------------------------------------------------------------------------
| Punto de entrada único del API Gateway
|--------------------------------------------------------------------------
*/

Route::prefix('health')
    ->group(base_path('routes/api/health.php'));

Route::prefix('orgtrack')
    ->group(base_path('routes/api/orgtrack.php'));

Route::prefix('trazabilidad')
    ->group(base_path('routes/api/trazabilidad.php'));
