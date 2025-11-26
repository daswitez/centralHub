<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Middleware de protección CSRF.
 *
 * - Protege formularios web (Blade) frente a ataques CSRF.
 * - Excluye de la verificación ciertas rutas usadas como API
 *   por la app móvil, donde no se manejan cookies/sesiones de navegador.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Nota:
     * - Aquí se listan prefijos de rutas que se usarán desde la app móvil
     *   (React Native) sin cookie de sesión.
     * - Estas rutas siguen pasando por validaciones, reglas, etc.,
     *   simplemente no exigen el token CSRF de los formularios Blade.
     *
     * @var array<int, string>
     */
    protected $except = [
        'cat/*',    // todos los CRUD de catálogos base
        'campo/*',  // CRUD orientados a campo (productores, lotes, lecturas)
        'tx/*',     // transacciones de planta y almacén
    ];
}


