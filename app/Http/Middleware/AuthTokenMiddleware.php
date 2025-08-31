<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        // Exemplo: verifica se existe token (ideal: validar JWT ou buscar em banco)
        if (!$token || strlen($token) < 10) {
            return response()->json(['message' => 'Não autorizado'], 401);
        }
        return $next($request);
    }
}
