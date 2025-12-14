<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MicroserviceClient;
use Illuminate\Http\JsonResponse;
use Exception;

class OrgTrackProxyController extends Controller
{
    protected MicroserviceClient $orgtrackClient;

    public function __construct()
    {
        $this->orgtrackClient = new MicroserviceClient('orgtrack');
    }

    /**
     * Proxy para listar todos los envíos públicos
     * Endpoint: GET /public/envios/all
     */
    public function getEnvios(): JsonResponse
    {
        try {
            $envios = $this->orgtrackClient->get('/public/envios/all');

            return response()->json([
                'success' => true,
                'data' => $envios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'url' => config('microservices.services.orgtrack.base_url'),
            ], 500);
        }
    }

    /**
     * Proxy para obtener seguimiento de un envío público
     * Endpoint: GET /public/envios/{id}/seguimiento
     */
    public function getEnvio(int $id): JsonResponse
    {
        try {
            $envio = $this->orgtrackClient->get("/public/envios/{$id}/seguimiento");

            return response()->json([
                'success' => true,
                'data' => $envio,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para listar envíos públicos de productores
     * Endpoint: GET /public/envios
     */
    public function getEnviosProductores(): JsonResponse
    {
        try {
            $envios = $this->orgtrackClient->get('/public/envios');

            return response()->json([
                'success' => true,
                'data' => $envios,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para obtener documento público de un envío
     * Endpoint: GET /public/envios/{id_envio}/documento
     */
    public function getDocumentoEnvio(int $id_envio): JsonResponse
    {
        try {
            $documento = $this->orgtrackClient->get("/public/envios/{$id_envio}/documento");

            return response()->json([
                'success' => true,
                'data' => $documento,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para obtener recursos disponibles para envíos de productores
     * Endpoint: GET /envios/productor/recursos-disponibles
     */
    public function getRecursosDisponibles(): JsonResponse
    {
        try {
            $recursos = $this->orgtrackClient->get('/envios/productor/recursos-disponibles');

            return response()->json([
                'success' => true,
                'data' => $recursos,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proxy para listar tipos de transporte (público)
     * Endpoint: GET /tipo-transporte
     */
    public function getTiposTransporte(): JsonResponse
    {
        try {
            $tipos = $this->orgtrackClient->get('/tipo-transporte');

            return response()->json([
                'success' => true,
                'data' => $tipos,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * =========================
     * CATÁLOGOS PÚBLICOS
     * =========================
     */

    public function getCatalogoCategorias(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get('/catalogo-categorias'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoCategoria(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get("/catalogo-categorias/{$id}"),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoProductos(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get('/catalogo-productos'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoProducto(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get("/catalogo-productos/{$id}"),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoTiposEmpaque(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get('/catalogo-tipos-empaque'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoTipoEmpaque(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get("/catalogo-tipos-empaque/{$id}"),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCatalogoTamanoConteo(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->orgtrackClient->get('/catalogo-tamano-conteo'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
