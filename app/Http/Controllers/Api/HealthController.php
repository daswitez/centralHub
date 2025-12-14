<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MicroserviceClient;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Verifica el estado de salud del API Gateway
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'API Gateway - CentralHub',
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
        ]);
    }

    /**
     * Verifica el estado de todos los microservicios
     */
    public function microservices(): JsonResponse
    {
        $services = config('microservices.services', []);
        $results = [];

        foreach (array_keys($services) as $serviceName) {
            try {
                $client = new MicroserviceClient($serviceName);
                $results[$serviceName] = $client->health();
            } catch (\Exception $e) {
                $results[$serviceName] = [
                    'service' => $serviceName,
                    'status' => 'error',
                    'error' => $e->getMessage(),
                    'checked_at' => now()->toIso8601String(),
                ];
            }
        }

        $allHealthy = collect($results)->every(fn($r) => $r['status'] === 'healthy');

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'degraded',
            'gateway' => 'API Gateway - CentralHub',
            'timestamp' => now()->toIso8601String(),
            'services' => $results,
        ], $allHealthy ? 200 : 503);
    }

    /**
     * Verifica el estado de un microservicio específico
     */
    public function service(string $serviceName): JsonResponse
    {
        try {
            $client = new MicroserviceClient($serviceName);
            $health = $client->health();

            return response()->json($health, $health['status'] === 'healthy' ? 200 : 503);
        } catch (\Exception $e) {
            return response()->json([
                'service' => $serviceName,
                'status' => 'error',
                'error' => $e->getMessage(),
                'checked_at' => now()->toIso8601String(),
            ], 500);
        }
    }
}
