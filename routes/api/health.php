<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;

Route::get('/', [HealthController::class, 'index']);
Route::get('/microservices', [HealthController::class, 'microservices']);
Route::get('/microservices/{service}', [HealthController::class, 'service']);
