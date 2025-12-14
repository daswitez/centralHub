<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HealthController;

/*
|--------------------------------------------------------------------------
| API Gateway Routes
|--------------------------------------------------------------------------
|
| Este archivo define las rutas del API Gateway que orquesta las llamadas
| a los microservicios. El gateway actúa como punto de entrada único para
| consumir múltiples microservicios.
|
*/

// ============================================================================
// Health Checks
// ============================================================================

Route::prefix('health')->group(function () {
    // Estado del API Gateway
    Route::get('/', [HealthController::class, 'index']);

    // Estado de todos los microservicios
    Route::get('/microservices', [HealthController::class, 'microservices']);

    // Estado de un microservicio específico
    Route::get('/microservices/{service}', [HealthController::class, 'service']);
});

// ============================================================================
// OrgTrack Proxy Routes (for frontend consumption)
// ============================================================================

Route::prefix('orgtrack')->group(function () {

    // ========================
    // ENVÍOS PÚBLICOS
    // ========================

    // Listar todos los envíos públicos
    Route::get('/envios/all', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getEnvios'
    ]);

    // Seguimiento de un envío público
    Route::get('/envios/{id}/seguimiento', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getEnvio'
    ]);

    // Listar envíos públicos de productores
    Route::get('/envios-productores', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getEnviosProductores'
    ]);

    // Documento público de un envío
    Route::get('/envios/{id_envio}/documento', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getDocumentoEnvio'
    ]);

    // Recursos disponibles para crear envíos (productores)
    Route::get('/envios/productor/recursos-disponibles', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getRecursosDisponibles'
    ]);

    // ========================
    // TIPOS DE TRANSPORTE
    // ========================

    Route::get('/tipo-transporte', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getTiposTransporte'
    ]);

    // ========================
    // CATÁLOGOS PÚBLICOS
    // ========================

    // Categorías
    Route::get('/catalogo-categorias', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoCategorias'
    ]);

    Route::get('/catalogo-categorias/{id}', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoCategoria'
    ]);

    // Productos
    Route::get('/catalogo-productos', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoProductos'
    ]);

    Route::get('/catalogo-productos/{id}', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoProducto'
    ]);

    // Tipos de empaque
    Route::get('/catalogo-tipos-empaque', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoTiposEmpaque'
    ]);

    Route::get('/catalogo-tipos-empaque/{id}', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoTipoEmpaque'
    ]);

    // Tamaño / conteo
    Route::get('/catalogo-tamano-conteo', [
        App\Http\Controllers\Api\OrgTrackProxyController::class,
        'getCatalogoTamanoConteo'
    ]);
});


// ============================================================================
// Trazabilidad Proxy Routes (for frontend consumption)
// ============================================================================

Route::prefix('trazabilidad')->group(function () {

    // ========================
    // PRODUCTS
    // ========================
    Route::get('/products', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getProducts'
    ]);

    Route::get('/products/{id}', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getProduct'
    ]);

    // ========================
    // CUSTOMER ORDERS
    // ========================
    Route::get('/customer-orders/by-user', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getOrdersByUser'
    ]);

    // ========================
    // PEDIDOS (TRAZABILIDAD)
    // ========================
    Route::get('/pedidos/completo', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getOrdersCompleto'
    ]);

    Route::get('/pedidos/{id}/completo', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getOrderCompleto'
    ]);

    Route::get('/pedidos/by-envio/{envioId}', [
        App\Http\Controllers\Api\TrazabilidadProxyController::class,
        'getOrderByEnvio'
    ]);
});


// ============================================================================
// Microservices Proxy Routes
// ============================================================================

/*
 * Las rutas de proxy a microservicios se agregarán aquí.
 * Ejemplo:
 * 
 * Route::prefix('trazabilidad')->group(function () {
 *     Route::get('/pedidos', [TrazabilidadController::class, 'getPedidos']);
 *     Route::post('/pedidos', [TrazabilidadController::class, 'createPedido']);
 * });
 */
