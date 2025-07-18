<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                // Registrar intento de acceso no autorizado
                Log::warning('Intento de acceso no autorizado', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                    'method' => $request->method()
                ]);

                // Si es una petición de API, devolver error 401
                if ($request->wantsJson() || $request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'No autenticado',
                        'redirect' => route('login')
                    ], 401);
                }

                // Redirigir al login para peticiones web
                return redirect()->guest(route('login'))->with('error', 'Por favor inicia sesión para acceder a esta página.');
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
