<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TrazabilidadProxyController;

/*
|--------------------------------------------------------------------------
| Trazabilidad Proxy Routes
|--------------------------------------------------------------------------
| Rutas del API Gateway que actúan como proxy hacia el microservicio
| de Trazabilidad.
|
| Prefijo base: /api/trazabilidad
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PRODUCTOS
|--------------------------------------------------------------------------
| Catálogo de productos y detalle individual para trazabilidad.
|--------------------------------------------------------------------------
*/

/**
 * Listar productos
 * GET /api/trazabilidad/products
 *
 * Respuesta (ejemplo):
 * {
 *   "current_page": 1,
 *   "data": [
 *     {
 *       "producto_id": 11,
 *       "codigo": "ACEITE-COCO-300ML",
 *       "nombre": "Aceite de Coco Univalle 300 ml",
 *       "peso": "0.30",
 *       "precio_unitario": "42.00"
 *     }
 *   ],
 *   "total": 20
 * }
 */
Route::get('/products', [
    TrazabilidadProxyController::class,
    'getProducts'
]);

/**
 * Obtener producto por ID
 * GET /api/trazabilidad/products/{id}
 *
 * Respuesta (ejemplo):
 * {
 *   "producto_id": 11,
 *   "codigo": "ACEITE-COCO-300ML",
 *   "nombre": "Aceite de Coco Univalle 300 ml",
 *   "peso": "0.30",
 *   "precio_unitario": "42.00",
 *   "unidad": {
 *     "codigo": "L",
 *     "nombre": "Litro"
 *   }
 * }
 */
Route::get('/products/{id}', [
    TrazabilidadProxyController::class,
    'getProduct'
]);

/*
|--------------------------------------------------------------------------
| CUSTOMER ORDERS
|--------------------------------------------------------------------------
| Consultas de pedidos por cliente/usuario.
|--------------------------------------------------------------------------
*/

/**
 * Pedidos del cliente autenticado
 * GET /api/trazabilidad/customer-orders/by-user
 *
 * Nota:
 * Requiere usuario autenticado en el sistema origen.
 *
 * Respuesta (ejemplo):
 * [
 *   {
 *     "pedido_id": 1,
 *     "numero_pedido": "PED-0001-20251214",
 *     "estado": "pendiente"
 *   }
 * ]
 */
Route::get('/customer-orders/by-user', [
    TrazabilidadProxyController::class,
    'getOrdersByUser'
]);

/*
|--------------------------------------------------------------------------
| PEDIDOS (TRAZABILIDAD)
|--------------------------------------------------------------------------
| Información completa de pedidos, productos, destinos y resumen.
|--------------------------------------------------------------------------
*/

/**
 * Listar pedidos completos
 * GET /api/trazabilidad/pedidos/completo
 *
 * Respuesta (ejemplo):
 * {
 *   "success": true,
 *   "total": 1,
 *   "pedidos": [
 *     {
 *       "pedido": { "numero_pedido": "PED-0001-20251214" },
 *       "cliente": { "razon_social": "Cliente X" },
 *       "resumen": {
 *         "total_productos": 32,
 *         "subtotal": 10986
 *       }
 *     }
 *   ]
 * }
 */
Route::get('/pedidos/completo', [
    TrazabilidadProxyController::class,
    'getOrdersCompleto'
]);

/**
 * Obtener pedido completo por ID
 * GET /api/trazabilidad/pedidos/{id}/completo
 *
 * Respuesta (ejemplo):
 * {
 *   "pedido": { "numero_pedido": "PED-0001-20251214" },
 *   "productos": [...],
 *   "destinos": [...],
 *   "resumen": {...}
 * }
 */
Route::get('/pedidos/{id}/completo', [
    TrazabilidadProxyController::class,
    'getOrderCompleto'
]);

/**
 * Obtener pedido asociado a un envío
 * GET /api/trazabilidad/pedidos/by-envio/{envioId}
 *
 * Respuesta (ejemplo):
 * {
 *   "pedido": { "numero_pedido": "PED-0001-20251214" },
 *   "envio_id": 31,
 *   "estado": "pendiente"
 * }
 */
Route::get('/pedidos/by-envio/{envioId}', [
    TrazabilidadProxyController::class,
    'getOrderByEnvio'
]);
