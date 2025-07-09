<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visita;

class ContadorVisitas
{
    public function handle($request, Closure $next)
{
    // Sólo contar visitas a páginas normales (GET)
    if (
        $request->isMethod('get')
        && ! $request->is('build/*')
        && ! $request->is('api/*')
        && ! $request->is('favicon.ico')
        && ! $request->is('_debugbar*')
        && ! $request->ajax()
    ) {
        $ruta = $request->path();
        if ($ruta === '/' || $ruta === '') $ruta = 'inicio';

        $visita = \App\Models\Visita::firstOrCreate(['ruta' => $ruta]);
        $visita->increment('contador');
    }

    return $next($request);
    }
}

