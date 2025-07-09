<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Visita;
use Inertia\Inertia;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    Inertia::share('menu', function () {
        $user = Auth::user();
        if (!$user) return [];
        return Menu::where('rol', $user->tipo_usuario)
            ->where('activo', true)
            ->orderBy('orden')
            ->get();
        });

        Inertia::share('contador_visitas', function () {
            $ruta = request()->path();
            if ($ruta === '' || $ruta === '/') $ruta = 'inicio';
            $visita = Visita::where('ruta', $ruta)->first();
            return $visita ? $visita->contador : 0;
        });
    }
}
