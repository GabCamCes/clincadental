<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Verifica que el usuario tenga el rol adecuado.
     * Uso: ->middleware('checkrole:A') para admin, 'M' para medico, 'P' para paciente
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        // Si no autenticado o el tipo_usuario NO está en la lista de roles permitidos
        if (!$user || !in_array($user->tipo_usuario, $roles)) {
            abort(403, 'No autorizado.');
        }
        return $next($request);
    }
}
