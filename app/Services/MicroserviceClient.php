<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class MicroserviceClient
{
    protected string $serviceName;
    protected array $config;

    public function __construct(string $serviceName)
    {
        $this->serviceName = $serviceName;
        $this->config = config("microservices.services.{$serviceName}");

        if (!$this->config) {
            throw new Exception("Microservicio '{$serviceName}' no está configurado");
        }
    }

    /**
     * Realiza una petición GET al microservicio
     */
    public function get(string $endpoint, array $params = [], array $options = [])
    {
        return $this->request('GET', $endpoint, [
            'query' => $params,
        ], $options);
    }

    /**
     * Realiza una petición POST al microservicio
     */
    public function post(string $endpoint, array $data = [], array $options = [])
    {
        return $this->request('POST', $endpoint, [
            'json' => $data,
        ], $options);
    }

    /**
     * Realiza una petición PUT al microservicio
     */
    public function put(string $endpoint, array $data = [], array $options = [])
    {
        return $this->request('PUT', $endpoint, [
            'json' => $data,
        ], $options);
    }

    /**
     * Realiza una petición DELETE al microservicio
     */
    public function delete(string $endpoint, array $options = [])
    {
        return $this->request('DELETE', $endpoint, [], $options);
    }

    /**
     * Verifica el estado de salud del microservicio
     */
    public function health(): array
    {
        $cacheKey = "health:{$this->serviceName}";
        $cacheTtl = config('microservices.health_check.cache_ttl', 30);

        return Cache::remember($cacheKey, $cacheTtl, function () {
            try {
                $healthEndpoint = $this->config['health'] ?? '/health';
                $url = $this->buildUrl($healthEndpoint);

                $response = Http::timeout(5)->get($url);

                return [
                    'service' => $this->serviceName,
                    'name' => $this->config['name'] ?? $this->serviceName,
                    'status' => $response->successful() ? 'healthy' : 'unhealthy',
                    'code' => $response->status(),
                    'response_time' => $response->transferStats?->getTransferTime() ?? null,
                    'checked_at' => now()->toIso8601String(),
                ];
            } catch (Exception $e) {
                return [
                    'service' => $this->serviceName,
                    'name' => $this->config['name'] ?? $this->serviceName,
                    'status' => 'unreachable',
                    'error' => $e->getMessage(),
                    'checked_at' => now()->toIso8601String(),
                ];
            }
        });
    }

    /**
     * Realiza la petición HTTP con circuit breaker y retry
     */
    protected function request(string $method, string $endpoint, array $data = [], array $options = [])
    {
        // Verificar circuit breaker
        if ($this->isCircuitOpen()) {
            throw new Exception("Circuit breaker abierto para {$this->serviceName}");
        }

        $url = $this->buildUrl($endpoint);
        $cacheKey = $this->getCacheKey($method, $endpoint, $data);
        $useCache = $options['cache'] ?? $this->config['cache']['enabled'] ?? false;

        // Intentar obtener de caché para peticiones GET
        if ($method === 'GET' && $useCache) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                $this->log('Cache hit', $method, $endpoint);
                return $cached;
            }
        }

        try {
            $response = $this->executeRequest($method, $url, $data);

            // Registrar éxito en circuit breaker
            $this->recordSuccess();

            // Cachear respuesta si es GET
            if ($method === 'GET' && $useCache) {
                $ttl = $this->config['cache']['ttl'] ?? 300;
                Cache::put($cacheKey, $response, $ttl);
            }

            return $response;

        } catch (Exception $e) {
            // Registrar fallo en circuit breaker
            $this->recordFailure();

            $this->log('Request failed', $method, $endpoint, [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Ejecuta la petición HTTP con reintentos
     */
    protected function executeRequest(string $method, string $url, array $data)
    {
        $timeout = $this->config['timeout'] ?? 10;
        $retry = $this->config['retry'] ?? 3;

        $this->log('Request', $method, $url, $data);

        $http = Http::timeout($timeout)
            ->retry($retry, 100)
            ->withHeaders($this->getAuthHeaders());

        // Desactivar verificación SSL en desarrollo
        if (config('app.env') !== 'production') {
            $http = $http->withOptions([
                'verify' => false,
            ]);
        }

        $response = match ($method) {
            'GET' => $http->get($url, $data['query'] ?? []),
            'POST' => $http->post($url, $data['json'] ?? []),
            'PUT' => $http->put($url, $data['json'] ?? []),
            'DELETE' => $http->delete($url),
            default => throw new Exception("Método HTTP no soportado: {$method}"),
        };

        if (!$response->successful()) {
            throw new Exception(
                "Error en microservicio {$this->serviceName}: " .
                $response->status() . " - " .
                $response->body()
            );
        }

        $this->log('Response', $method, $url, [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return $response->json();
    }

    /**
     * Construye la URL completa del endpoint
     */
    protected function buildUrl(string $endpoint): string
    {
        $baseUrl = rtrim($this->config['base_url'], '/');
        $endpoint = ltrim($endpoint, '/');
        return "{$baseUrl}/{$endpoint}";
    }

    /**
     * Obtiene los headers de autenticación según configuración
     */
    protected function getAuthHeaders(): array
    {
        $authConfig = $this->config['auth'] ?? [];
        $authType = $authConfig['type'] ?? 'none';

        return match ($authType) {
            'jwt', 'bearer' => [
                'Authorization' => 'Bearer ' . env($authConfig['token_key'] ?? ''),
            ],
            'basic' => [
                'Authorization' => 'Basic ' . base64_encode(
                    env($authConfig['username_key'] ?? '') . ':' .
                    env($authConfig['password_key'] ?? '')
                ),
            ],
            default => [],
        };
    }

    /**
     * Genera clave de caché para la petición
     */
    protected function getCacheKey(string $method, string $endpoint, array $data): string
    {
        $prefix = config('microservices.cache.key_prefix', 'microservice:');
        $hash = md5(json_encode([$method, $endpoint, $data]));
        return "{$prefix}{$this->serviceName}:{$hash}";
    }

    /**
     * Verifica si el circuit breaker está abierto
     */
    protected function isCircuitOpen(): bool
    {
        if (!($this->config['circuit_breaker']['enabled'] ?? false)) {
            return false;
        }

        $key = $this->getCircuitBreakerKey();
        $state = Cache::get($key);

        return $state === 'open';
    }

    /**
     * Registra un éxito en el circuit breaker
     */
    protected function recordSuccess(): void
    {
        if (!($this->config['circuit_breaker']['enabled'] ?? false)) {
            return;
        }

        $key = $this->getCircuitBreakerKey();
        Cache::forget($key);
        Cache::forget("{$key}:failures");
    }

    /**
     * Registra un fallo en el circuit breaker
     */
    protected function recordFailure(): void
    {
        if (!($this->config['circuit_breaker']['enabled'] ?? false)) {
            return;
        }

        $key = $this->getCircuitBreakerKey();
        $failuresKey = "{$key}:failures";
        $threshold = $this->config['circuit_breaker']['failure_threshold'] ?? 5;
        $timeout = $this->config['circuit_breaker']['timeout'] ?? 60;

        $failures = Cache::get($failuresKey, 0) + 1;
        Cache::put($failuresKey, $failures, $timeout);

        if ($failures >= $threshold) {
            Cache::put($key, 'open', $timeout);
            $this->log('Circuit breaker opened', 'SYSTEM', $this->serviceName, [
                'failures' => $failures,
                'threshold' => $threshold,
            ]);
        }
    }

    /**
     * Obtiene la clave del circuit breaker
     */
    protected function getCircuitBreakerKey(): string
    {
        $prefix = config('microservices.circuit_breaker.key_prefix', 'circuit_breaker:');
        return "{$prefix}{$this->serviceName}";
    }

    /**
     * Registra eventos en el log
     */
    protected function log(string $event, string $method, string $endpoint, array $context = []): void
    {
        if (!config('microservices.logging.enabled', true)) {
            return;
        }

        $channel = config('microservices.logging.channel', 'microservices');

        Log::channel($channel)->info("[{$this->serviceName}] {$event}", [
            'service' => $this->serviceName,
            'method' => $method,
            'endpoint' => $endpoint,
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }
}
