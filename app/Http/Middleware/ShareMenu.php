<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareMenu
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user) {
            $menus = \App\Models\Menu::orderBy('orden')
                ->get()
                ->filter(function ($item) use ($user) {
                    // Verifica si el rol del usuario está incluido en 'roles'
                    return $item->roles && str_contains($item->roles, $user->tipo_usuario);
                })
                ->values(); // Limpia keys

            Inertia::share('menu', $menus);
        }
        return $next($request);
    }
}
