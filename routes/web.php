<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\PagoController;
use Illuminate\Http\Request;

// Ruta raíz con manejo de autenticación
Route::get('/', function () {
    // Verificar si el usuario está autenticado
    if (auth()->check()) {
        // Redirigir según el tipo de usuario
        return match(auth()->user()->tipo_usuario) {
            'A' => redirect()->route('usuario.dashboard'),
            'M' => redirect()->route('medico.dashboard'),
            'P' => redirect()->route('paciente.dashboard'),
            default => redirect()->route('login')
        };
    }

    // Mostrar página de bienvenida para usuarios no autenticados
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

//Route::get('/pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');


Route::get('/pagos/{pago}/qr', [\App\Http\Controllers\PagoController::class, 'showQr'])
    ->name('pagos.qr')
    ->middleware(['auth']); // Solo usuarios logueados


// Si tienes rutas de login/logout, déjalas aquí y NO las metas en grupos con auth o rol.
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');


// Rutas de usuario autenticado pero SIN rol específico (ej: perfil)
Route::middleware(['auth'])->group(function () {
    // Eliminada la ruta de dashboard por defecto para evitar redirecciones no deseadas


});

// Rutas SOLO para administradores
Route::middleware(['auth', 'checkrole:A'])->group(function () {
    Route::get('/usuario', [\App\Http\Controllers\EstadisticasController::class, 'dashboard'])
        ->name('usuario.dashboard');
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('especialidades', EspecialidadController::class)->parameters(['especialidades' => 'especialidad']);
    Route::resource('salas', SalaController::class)->parameters(['salas' => 'sala']);
    Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);
    Route::resource('servicios', ServicioController::class)->parameters(['servicios' => 'servicio']);
    Route::resource('pacientes', PacienteController::class)->parameters(['pacientes' => 'paciente']);
    Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
    Route::resource('promociones', PromocionController::class)->parameters(['promociones' => 'promocion']);
    //Route::resource('citas', \App\Http\Controllers\CitaController::class);
    //Route::resource('horarios-atencion', \App\Http\Controllers\HorarioAtencionController::class);
    //Route::resource('historial-clinico', \App\Http\Controllers\HistorialClinicoController::class);
    Route::resource('ventas', \App\Http\Controllers\VentaController::class);
    Route::resource('pagos', \App\Http\Controllers\PagoController::class);
    Route::get('historial-pagos', [\App\Http\Controllers\HistorialPagoController::class, 'index'])->name('historial-pagos.index');
    
});

Route::middleware(['auth', 'checkrole:P'])->group(function () {
    Route::get('/paciente', function () {
        return Inertia::render('Pacientes/Dashboard');
    })->name('paciente.dashboard');

    // Pagos: los pacientes solo pueden visualizar los suyos
    Route::resource('pagos', PagoController::class)->only(['index', 'show']);
});
Route::middleware(['auth', 'checkrole:M'])->group(function () {
    Route::get('/medico', function () {
        return Inertia::render('Medicos/Dashboard');
    })->name('medico.dashboard');
    
    // Ruta para que los médicos vean sus horarios de atención
    Route::get('/medico/horarios', [\App\Http\Controllers\HorarioAtencionController::class, 'index'])
        ->name('medico.horarios.index');
});

// Acceso a citas e historial clínico para admin, médico y paciente
Route::middleware(['auth', 'checkrole:A,M,P'])->group(function () {
    Route::resource('citas', \App\Http\Controllers\CitaController::class);
    Route::resource('historial-clinico', \App\Http\Controllers\HistorialClinicoController::class);
});

// Rutas de horarios de atención accesibles para administradores y médicos
Route::middleware(['auth', 'checkrole:A,M'])->group(function () {
    // Los administradores pueden ver todos los horarios
    Route::get('horarios-atencion', [\App\Http\Controllers\HorarioAtencionController::class, 'index'])
        ->name('horarios-atencion.index');
        
    Route::get('horarios-atencion/create', [\App\Http\Controllers\HorarioAtencionController::class, 'create'])
        ->name('horarios-atencion.create');
        
    Route::post('horarios-atencion', [\App\Http\Controllers\HorarioAtencionController::class, 'store'])
        ->name('horarios-atencion.store');
        
    Route::get('horarios-atencion/{horario_atencion}/edit', [\App\Http\Controllers\HorarioAtencionController::class, 'edit'])
        ->name('horarios-atencion.edit');
        
    Route::put('horarios-atencion/{horario_atencion}', [\App\Http\Controllers\HorarioAtencionController::class, 'update'])
        ->name('horarios-atencion.update');
        
    Route::delete('horarios-atencion/{horario_atencion}', [\App\Http\Controllers\HorarioAtencionController::class, 'destroy'])
        ->name('horarios-atencion.destroy');
});

// Rutas específicas para médicos (solo sus propios horarios)




Route::get('/buscar', function(Request $request) {
    $q = strtolower(trim($request->query('query', '')));

    // Preparar variables vacías
    $pacientes = $medicos = $productos = $servicios = $citas = $promociones = $especialidades = $salas = $usuarios = [];

    // Si busca "pacientes"
    if ($q === 'pacientes') {
        $pacientes = \App\Models\Paciente::with('usuario')->get();
    }
    // Si busca "medicos" o "médicos"
    else if ($q === 'medicos' || $q === 'médicos') {
        $medicos = \App\Models\Medico::with('usuario')->get();
    }
    // Si busca "productos"
    else if ($q === 'productos') {
        $productos = \App\Models\Producto::all();
    }
    // Si busca "servicios"
    else if ($q === 'servicios') {
        $servicios = \App\Models\Servicio::all();
    }
    // Si busca "promociones"
    else if ($q === 'promociones') {
        $promociones = \App\Models\Promocion::all();
    }
    // Si busca "especialidades"
    else if ($q === 'especialidades') {
        $especialidades = \App\Models\Especialidad::all();
    }
    // Si busca "salas"
    else if ($q === 'salas') {
        $salas = \App\Models\Sala::all();
    }
    // Si busca "usuarios"
    else if ($q === 'usuarios') {
        $usuarios = \App\Models\Usuario::all();
    }
    // Si busca "citas"
    else if ($q === 'citas') {
        $citas = \App\Models\Cita::with('paciente.usuario', 'medico.usuario', 'servicio')->get();
    }
    // Búsqueda normal
    else if (!empty($q)) {
        // ... (Aquí tu código normal de búsquedas por texto, como ya lo tienes)
        $pacientes = \App\Models\Paciente::with('usuario')
            ->whereHas('usuario', function($query) use ($q) {
                $query->where('nombres', 'ilike', "%$q%")
                      ->orWhere('apellido_paterno', 'ilike', "%$q%")
                      ->orWhere('apellido_materno', 'ilike', "%$q%");
            })->get();
        $medicos = \App\Models\Medico::with('usuario')
            ->whereHas('usuario', function($query) use ($q) {
                $query->where('nombres', 'ilike', "%$q%")
                      ->orWhere('apellido_paterno', 'ilike', "%$q%")
                      ->orWhere('apellido_materno', 'ilike', "%$q%");
            })->get();
        $productos = \App\Models\Producto::where('nombre', 'ilike', "%$q%")
            ->orWhere('descripcion', 'ilike', "%$q%")
            ->orWhere('precio', 'ilike', "%$q%")
            ->get();
        $servicios = \App\Models\Servicio::where('nombre', 'ilike', "%$q%")
            ->orWhere('descripcion', 'ilike', "%$q%")
            ->orWhere('precio', 'ilike', "%$q%")
            ->get();
        $promociones = \App\Models\Promocion::where('titulo', 'ilike', "%$q%")
            ->orWhere('descripcion', 'ilike', "%$q%")
            ->get();
        $especialidades = \App\Models\Especialidad::where('nombre', 'ilike', "%$q%")->get();
        $salas = \App\Models\Sala::where('nombre', 'ilike', "%$q%")
            ->orWhere('descripcion', 'ilike', "%$q%")
            ->get();
        $usuarios = \App\Models\Usuario::where('nombres', 'ilike', "%$q%")
            ->orWhere('apellido_paterno', 'ilike', "%$q%")
            ->orWhere('apellido_materno', 'ilike', "%$q%")
            ->orWhere('ci', 'ilike', "%$q%")
            ->get();
    }

    return Inertia::render('Buscar/Resultados', [
        'query' => $q,
        'pacientes' => $pacientes,
        'medicos' => $medicos,
        'productos' => $productos,
        'servicios' => $servicios,
        'citas' => $citas,
        'promociones' => $promociones,
        'especialidades' => $especialidades,
        'salas' => $salas,
        'usuarios' => $usuarios,
    ]);
});

Route::middleware(['auth', 'checkrole:A'])->group(function () {
    // Ruta para las estadísticas del administrador
    Route::get('/estadisticas', [App\Http\Controllers\EstadisticasController::class, 'index'])
        ->name('estadisticas.index');
    
    // Ruta para el dashboard del administrador
    Route::get('/usuario/dashboard', [App\Http\Controllers\EstadisticasController::class, 'dashboard'])
        ->name('usuario.dashboard');
});



require __DIR__.'/auth.php';


