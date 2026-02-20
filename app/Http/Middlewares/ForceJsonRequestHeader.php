<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ForceJsonRequestHeader
 * @package App\Http\Middlewares
 *
 * Middleware para forzar que la petición tenga el header 'Accept' con valor 'application/json'.
 */
class ForceJsonRequestHeader
{
    /**
     * Maneja una petición entrante.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     *
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        
        return $next($request);
    }
}