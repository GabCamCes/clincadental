<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar la vista de login.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Procesar la solicitud de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
    $request->authenticate();
    $request->session()->regenerate();

    $user = \Auth::user();
    
    // Usar match para mejor legibilidad
    $redirectTo = match($user->tipo_usuario) {
        'A' => 'usuario.dashboard',
        'M' => 'medico.dashboard',
        'P' => 'paciente.dashboard',
        default => null
    };
    
    if ($redirectTo) {
        // Si el usuario intentó acceder a una ruta protegida, redirigir allí
        // De lo contrario, redirigir al dashboard correspondiente
        return redirect()->intended(route($redirectTo));
    }
    
    // Si el tipo de usuario no es reconocido, cerrar sesión y redirigir al login
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('login')
        ->with('error', 'Tipo de usuario no válido. Por favor, contacte al administrador.');
}

    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

