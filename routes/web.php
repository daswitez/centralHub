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
use App\Http\Controllers\Campo\SensorLecturaController;
use App\Http\Controllers\Planta\TransaccionPlantaController;
use App\Http\Controllers\Almacen\TransaccionAlmacenController;
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
    Route::resource('lecturas', SensorLecturaController::class)->except(['show']);
});

// Paneles (dashboards)
Route::prefix('panel')->name('panel.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/ventas', [DashboardController::class, 'ventas'])->name('ventas');
    Route::get('/logistica', [DashboardController::class, 'logistica'])->name('logistica');
    Route::get('/planta', [DashboardController::class, 'planta'])->name('planta');
    Route::get('/certificaciones', [DashboardController::class, 'certificaciones'])->name('certificaciones');
});

// API endpoints for transactions
Route::prefix('api')->group(function () {
    Route::get('/envios/buscar/{codigo}', [App\Http\Controllers\Almacen\TransaccionAlmacenController::class, 'buscarEnvio']);
});

// Transacciones de negocio (SPs en PostgreSQL)
Route::prefix('tx')->name('tx.')->group(function () {
    // Planta
    Route::prefix('planta')->name('planta.')->group(function () {
        Route::get('lote-planta', [TransaccionPlantaController::class, 'showLotePlantaForm'])
            ->name('lote-planta.form');
        Route::post('lote-planta', [TransaccionPlantaController::class, 'registrarLotePlanta'])
            ->name('lote-planta.store');

        Route::get('lote-salida-envio', [TransaccionPlantaController::class, 'showLoteSalidaEnvioForm'])
            ->name('lote-salida-envio.form');
        Route::post('lote-salida-envio', [TransaccionPlantaController::class, 'registrarLoteSalidaEnvio'])
            ->name('lote-salida-envio.store');
    });

    // Almacén
    Route::prefix('almacen')->name('almacen.')->group(function () {
        Route::get('despachar-al-almacen', [TransaccionAlmacenController::class, 'showDespacharAlmacenForm'])
            ->name('despachar-al-almacen.form');
        Route::post('despachar-al-almacen', [TransaccionAlmacenController::class, 'despacharAlmacen'])
            ->name('despachar-al-almacen.store');

        Route::get('recepcionar-envio', [TransaccionAlmacenController::class, 'showRecepcionarEnvioForm'])
            ->name('recepcionar-envio.form');
        Route::post('recepcionar-envio', [TransaccionAlmacenController::class, 'recepcionarEnvio'])
            ->name('recepcionar-envio.store');

        Route::get('despachar-al-cliente', [TransaccionAlmacenController::class, 'showDespacharClienteForm'])
            ->name('despachar-al-cliente.form');
        Route::post('despachar-al-cliente', [TransaccionAlmacenController::class, 'despacharCliente'])
            ->name('despachar-al-cliente.store');
    });
});