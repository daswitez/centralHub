<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrgTrackProxyController;

/*
|--------------------------------------------------------------------------
| OrgTrack Proxy Routes
|--------------------------------------------------------------------------
| Este archivo define las rutas públicas del API Gateway que actúan como
| proxy hacia el microservicio OrgTrack.
|
| Prefijo base: /api/orgtrack
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ENVÍOS PÚBLICOS
|--------------------------------------------------------------------------
| Endpoints accesibles sin autenticación para consultar información
| de envíos y su trazabilidad básica.
|--------------------------------------------------------------------------
*/

/**
 * Listar todos los envíos públicos
 * GET /api/orgtrack/envios/all
 *
 * Respuesta (ejemplo):
 * [
 *   {
 *     "id": 31,
 *     "estado": "Entregado",
 *     "fecha_creacion": "2025-12-13 10:22:35",
 *     "direccion_origen": "...",
 *     "direccion_destino": "..."
 *   }
 * ]
 */
Route::get('/envios/all', [
    OrgTrackProxyController::class,
    'getEnvios'
]);

/**
 * Obtener el seguimiento de un envío público
 * GET /api/orgtrack/envios/{id}/seguimiento
 *
 * Respuesta (ejemplo):
 * {
 *   "id": 31,
 *   "estado": "Entregado",
 *   "particiones": [...],
 *   "estado_resumen": "En curso (0 de 1 camiones activos)"
 * }
 */
Route::get('/envios/{id}/seguimiento', [
    OrgTrackProxyController::class,
    'getEnvio'
]);

/**
 * Listar envíos públicos de productores
 * GET /api/orgtrack/envios-productores
 *
 * Respuesta (ejemplo):
 * [
 *   {
 *     "id": 31,
 *     "estado": "Entregado",
 *     "coordenadas_origen": { "lat": ..., "lng": ... },
 *     "coordenadas_destino": { "lat": ..., "lng": ... }
 *   }
 * ]
 */
Route::get('/envios-productores', [
    OrgTrackProxyController::class,
    'getEnviosProductores'
]);

/**
 * Obtener documento público de un envío
 * GET /api/orgtrack/envios/{id_envio}/documento
 *
 * Respuesta (ejemplo):
 * {
 *   "id_envio": 31,
 *   "estado": "Entregado",
 *   "particiones": [...],
 *   "checklistCondiciones": [...],
 *   "checklistIncidentes": [...]
 * }
 */
Route::get('/envios/{id_envio}/documento', [
    OrgTrackProxyController::class,
    'getDocumentoEnvio'
]);

/*
|--------------------------------------------------------------------------
| TIPOS DE TRANSPORTE
|--------------------------------------------------------------------------
*/

/**
 * Listar tipos de transporte
 * GET /api/orgtrack/tipo-transporte
 *
 * Respuesta (ejemplo):
 * [
 *   { "id": 1, "nombre": "Refrigerado" },
 *   { "id": 2, "nombre": "Isotérmico" }
 * ]
 */
Route::get('/tipo-transporte', [
    OrgTrackProxyController::class,
    'getTiposTransporte'
]);

/*
|--------------------------------------------------------------------------
| CATÁLOGOS PÚBLICOS
|--------------------------------------------------------------------------
| Datos maestros usados por frontend (productos, categorías, empaques).
|--------------------------------------------------------------------------
*/

/**
 * Categorías
 * GET /api/orgtrack/catalogo-categorias
 */
Route::get('/catalogo-categorias', [
    OrgTrackProxyController::class,
    'getCatalogoCategorias'
]);

/**
 * Categoría por ID
 * GET /api/orgtrack/catalogo-categorias/{id}
 */
Route::get('/catalogo-categorias/{id}', [
    OrgTrackProxyController::class,
    'getCatalogoCategoria'
]);

/**
 * Productos
 * GET /api/orgtrack/catalogo-productos
 */
Route::get('/catalogo-productos', [
    OrgTrackProxyController::class,
    'getCatalogoProductos'
]);

/**
 * Producto por ID
 * GET /api/orgtrack/catalogo-productos/{id}
 */
Route::get('/catalogo-productos/{id}', [
    OrgTrackProxyController::class,
    'getCatalogoProducto'
]);

/**
 * Tipos de empaque
 * GET /api/orgtrack/catalogo-tipos-empaque
 */
Route::get('/catalogo-tipos-empaque', [
    OrgTrackProxyController::class,
    'getCatalogoTiposEmpaque'
]);

/**
 * Tipo de empaque por ID
 * GET /api/orgtrack/catalogo-tipos-empaque/{id}
 */
Route::get('/catalogo-tipos-empaque/{id}', [
    OrgTrackProxyController::class,
    'getCatalogoTipoEmpaque'
]);

/**
 * Tamaño / conteo
 * GET /api/orgtrack/catalogo-tamano-conteo
 */
Route::get('/catalogo-tamano-conteo', [
    OrgTrackProxyController::class,
    'getCatalogoTamanoConteo'
]);
