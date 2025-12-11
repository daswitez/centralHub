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



Route::prefix('cat')->group(function () {
    // Departamentos
    Route::get('departamentos', [DepartamentoController::class, 'index']);
    Route::post('departamentos', [DepartamentoController::class, 'store']);
    Route::put('departamentos/{id}', [DepartamentoController::class, 'update']);
    Route::delete('departamentos/{id}', [DepartamentoController::class, 'destroy']);

    // Municipios
    Route::get('municipios', [MunicipioController::class, 'index']);
    Route::post('municipios', [MunicipioController::class, 'store']);
    Route::put('municipios/{id}', [MunicipioController::class, 'update']);
    Route::delete('municipios/{id}', [MunicipioController::class, 'destroy']);

    // Variedades de papa
    Route::get('variedades', [VariedadPapaController::class, 'index']);
    Route::post('variedades', [VariedadPapaController::class, 'store']);
    Route::put('variedades/{id}', [VariedadPapaController::class, 'update']);
    Route::delete('variedades/{id}', [VariedadPapaController::class, 'destroy']);

    // Plantas
    Route::get('plantas', [PlantaController::class, 'index']);
    Route::post('plantas', [PlantaController::class, 'store']);
    Route::put('plantas/{id}', [PlantaController::class, 'update']);
    Route::delete('plantas/{id}', [PlantaController::class, 'destroy']);

    // Clientes
    Route::get('clientes', [ClienteController::class, 'index']);
    Route::post('clientes', [ClienteController::class, 'store']);
    Route::put('clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('clientes/{id}', [ClienteController::class, 'destroy']);

    // Transportistas
    Route::get('transportistas', [TransportistaController::class, 'index']);
    Route::post('transportistas', [TransportistaController::class, 'store']);
    Route::put('transportistas/{id}', [TransportistaController::class, 'update']);
    Route::delete('transportistas/{id}', [TransportistaController::class, 'destroy']);

    // Almacenes
    Route::get('almacenes', [AlmacenController::class, 'index']);
    Route::post('almacenes', [AlmacenController::class, 'store']);
    Route::put('almacenes/{id}', [AlmacenController::class, 'update']);
    Route::delete('almacenes/{id}', [AlmacenController::class, 'destroy']);
});

Route::prefix('campo')->group(function () {
    // Productores
    Route::get('productores', [ProductorController::class, 'index']);
    Route::post('productores', [ProductorController::class, 'store']);
    Route::put('productores/{id}', [ProductorController::class, 'update']);
    Route::delete('productores/{id}', [ProductorController::class, 'destroy']);

    // Lotes de campo
    Route::get('lotes', [LoteCampoController::class, 'index']);
    Route::post('lotes', [LoteCampoController::class, 'store']);
    Route::put('lotes/{id}', [LoteCampoController::class, 'update']);
    Route::delete('lotes/{id}', [LoteCampoController::class, 'destroy']);

    // Lecturas de sensor
    Route::get('lecturas', [SensorLecturaController::class, 'index']);
    Route::post('lecturas', [SensorLecturaController::class, 'store']);
    Route::put('lecturas/{id}', [SensorLecturaController::class, 'update']);
    Route::delete('lecturas/{id}', [SensorLecturaController::class, 'destroy']);
});

Route::prefix('tx')->group(function () {
    // Planta
    Route::prefix('planta')->group(function () {
        Route::post('lote-planta', [TransaccionPlantaController::class, 'registrarLotePlanta']);
        Route::post('lote-salida-envio', [TransaccionPlantaController::class, 'registrarLoteSalidaEnvio']);
    });

    // Almacén
    Route::prefix('almacen')->group(function () {
        Route::post('despachar-al-almacen', [TransaccionAlmacenController::class, 'despacharAlmacen']);
        Route::post('recepcionar-envio', [TransaccionAlmacenController::class, 'recepcionarEnvio']);
        Route::post('despachar-al-cliente', [TransaccionAlmacenController::class, 'despacharCliente']);
    });
});

