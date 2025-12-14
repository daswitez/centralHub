<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Microservicios
    |--------------------------------------------------------------------------
    |
    | Aquí se definen todos los microservicios que consume el API Gateway.
    | Cada microservicio tiene su configuración de URL base, caché, health check
    | y opciones de circuit breaker para resiliencia.
    |
    */

    'services' => [

        'trazabilidad' => [
            'name' => 'Sistema de Trazabilidad',
            'base_url' => env('TRAZABILIDAD_API_URL', 'http://localhost:8001/api'),
            'health' => '/health',
            'timeout' => 10, // segundos
            'retry' => 3,
            'cache' => [
                'enabled' => true,
                'ttl' => 300, // 5 minutos
            ],
            'circuit_breaker' => [
                'enabled' => true,
                'failure_threshold' => 5, // Fallos consecutivos antes de abrir circuito
                'timeout' => 60, // Tiempo en segundos antes de intentar de nuevo
            ],
            'auth' => [
                'type' => 'jwt', // jwt, bearer, basic, none
                'token_key' => 'TRAZABILIDAD_API_TOKEN',
            ],
        ],

        'orgtrack' => [
            'name' => 'Sistema OrgTrack',
            'base_url' => env('ORGTRACK_API_URL', 'http://localhost:8002/api'),
            'health' => '/health',
            'timeout' => 10,
            'retry' => 3,
            'cache' => [
                'enabled' => true,
                'ttl' => 300,
            ],
            'circuit_breaker' => [
                'enabled' => true,
                'failure_threshold' => 5,
                'timeout' => 60,
            ],
            'auth' => [
                'type' => 'none', // OrgTrack usa endpoints públicos
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración Global de Circuit Breaker
    |--------------------------------------------------------------------------
    */
    'circuit_breaker' => [
        'storage' => 'cache', // cache, redis, database
        'key_prefix' => 'circuit_breaker:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración Global de Caché
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'driver' => env('MICROSERVICES_CACHE_DRIVER', 'file'),
        'key_prefix' => 'microservice:',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Health Checks
    |--------------------------------------------------------------------------
    */
    'health_check' => [
        'enabled' => true,
        'interval' => 60, // Verificar cada 60 segundos
        'cache_ttl' => 30, // Cachear resultado por 30 segundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('MICROSERVICES_LOGGING', true),
        'channel' => 'microservices',
        'log_requests' => true,
        'log_responses' => true,
        'log_errors' => true,
    ],

];
