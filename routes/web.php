<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cat\DepartamentoController;
use App\Http\Controllers\Cat\MunicipioController;
use App\Http\Controllers\Cat\VariedadPapaController;
use App\Http\Controllers\Cat\PlantaController;
use App\Http\Controllers\Cat\ClienteController;
use App\Http\Controllers\Cat\TransportistaController;
use App\Http\Controllers\Cat\AlmacenController;
use App\Http\Controllers\Campo\ProductorController;
use App\Http\Controllers\Campo\LoteCampoController;
use App\Http\Controllers\Panel\DashboardController;

Route::get('/', function () {
    return redirect()->route('panel.home');
});

// Rutas CRUD para catálogos base (prefijo /cat)
Route::prefix('cat')->name('cat.')->group(function () {
    Route::resource('departamentos', DepartamentoController::class)->except(['show']);
    Route::resource('municipios', MunicipioController::class)->except(['show']);
    Route::resource('variedades', VariedadPapaController::class)->except(['show']);
    Route::resource('plantas', PlantaController::class)->except(['show']);
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::resource('transportistas', TransportistaController::class)->except(['show']);
    Route::resource('almacenes', AlmacenController::class)->except(['show']);
});

// Rutas CRUD para campo (prefijo /campo)
Route::prefix('campo')->name('campo.')->group(function () {
    Route::resource('productores', ProductorController::class)->except(['show']);
    Route::resource('lotes', LoteCampoController::class)->except(['show']);
});

// Paneles (dashboards)
Route::prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/ventas', [DashboardController::class, 'ventas'])->name('ventas');
    Route::get('/logistica', [DashboardController::class, 'logistica'])->name('logistica');
    Route::get('/planta', [DashboardController::class, 'planta'])->name('planta');
    Route::get('/certificaciones', [DashboardController::class, 'certificaciones'])->name('certificaciones');
});