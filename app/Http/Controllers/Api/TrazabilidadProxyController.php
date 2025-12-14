<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MicroserviceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class TrazabilidadProxyController extends Controller
{
    protected MicroserviceClient $trazabilidadClient;

    public function __construct()
    {
        $this->trazabilidadClient = new MicroserviceClient('trazabilidad');
    }

    /**
     * Proxy para listar productos
     * Endpoint: GET /api/products
     */
    public function getProducts(Request $request): JsonResponse
    {
        try {
            $params = $request->only(['tipo', 'activo', 'per_page']);
            $products = $this->trazabilidadClient->get('/products', $params);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'url' => config('microservices.services.trazabilidad.base_url'),
            ], 500);
        }
    }

    /**
     * Proxy para obtener un producto específico
     * Endpoint: GET /api/products/{id}
     */
    public function getProduct(int $id): JsonResponse
    {
        try {
            $product = $this->trazabilidadClient->get("/products/{$id}");

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para listar pedidos por nombre de usuario
     * Endpoint: GET /api/customer-orders/by-user
     */
    public function getOrdersByUser(Request $request): JsonResponse
    {
        try {
            $nombreUsuario = $request->query('nombre_usuario');

            if (!$nombreUsuario) {
                return response()->json([
                    'success' => false,
                    'error' => 'El parámetro nombre_usuario es requerido',
                ], 400);
            }

            $orders = $this->trazabilidadClient->get('/customer-orders/by-user', [
                'nombre_usuario' => $nombreUsuario
            ]);

            return response()->json([
                'success' => true,
                'data' => $orders['orders'] ?? [],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para listar todos los pedidos completos
     * Endpoint: GET /api/pedidos/completo
     */
    public function getOrdersCompleto(): JsonResponse
    {
        try {
            $orders = $this->trazabilidadClient->get('/pedidos/completo');

            return response()->json([
                'success' => true,
                'data' => $orders['pedidos'] ?? $orders,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para obtener un pedido completo por ID
     * Endpoint: GET /api/pedidos/{id}/completo
     */
    public function getOrderCompleto(int $id): JsonResponse
    {
        try {
            $order = $this->trazabilidadClient->get("/pedidos/{$id}/completo");

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para obtener pedido por envio_id
     * Endpoint: GET /api/pedidos/by-envio/{envioId}
     */
    public function getOrderByEnvio(string $envioId): JsonResponse
    {
        try {
            $order = $this->trazabilidadClient->get("/pedidos/by-envio/{$envioId}");

            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
