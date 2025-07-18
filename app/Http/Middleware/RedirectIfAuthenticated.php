<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                // Determinar la ruta de redirección según el tipo de usuario
                $redirectRoute = 'dashboard'; // Ruta por defecto
                
                switch ($user->tipo_usuario) {
                    case 'A': // Administrador
                        $redirectRoute = 'dashboard';
                        break;
                        
                    case 'M': // Médico
                        $redirectRoute = 'medico.dashboard';
                        break;
                        
                    case 'P': // Paciente
                        $redirectRoute = 'paciente.dashboard';
                        break;
                }
                
                // Si es una petición de API, devolver respuesta JSON
                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'message' => 'Ya estás autenticado',
                        'redirect' => route($redirectRoute),
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->nombre,
                            'email' => $user->correo,
                            'type' => $user->tipo_usuario
                        ]
                    ]);
                }
                
                // Redirigir según el tipo de usuario
                return redirect()->intended(route($redirectRoute));
            }
        }

        return $next($request);
    }
}
