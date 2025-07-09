<?php

namespace App\Http\Controllers;

use App\Models\HorarioAtencion;
use App\Models\Medico;
use App\Http\Requests\HorarioAtencionRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MedicoController;


class HorarioAtencionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Cargar la relación médico para el usuario actual si es médico
        if ($user->tipo_usuario === 'M') {
            $medico = Medico::with('usuario')->where('usuario_id', $user->id)->first();
            
            // Si no tiene perfil de médico, retornar vacío
            if (!$medico) {
                return inertia('HorariosAtencion/Index', [
                    'horarios' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
                    'auth' => [
                        'user' => array_merge($user->toArray(), ['medico' => null])
                    ]
                ]);
            }
            
            $horarios = HorarioAtencion::with('medico.usuario')
                ->where('medico_id', $medico->id)
                ->orderBy('dia_semana')
                ->orderBy('hora_inicio')
                ->paginate(10);
        } else {
            // Admin ve todos los horarios con la relación médico
            $horarios = HorarioAtencion::with('medico.usuario')
                ->orderBy('medico_id')
                ->orderBy('dia_semana')
                ->orderBy('hora_inicio')
                ->paginate(10);
        }

        // Incluir los datos del médico en la respuesta
        return inertia('HorariosAtencion/Index', [
            'horarios' => $horarios,
            'auth' => [
                'user' => array_merge($user->toArray(), [
                    'medico' => $user->tipo_usuario === 'M' ? $medico : null
                ])
            ]
        ]);
}

    public function create()
    {
        $user = Auth::user();

        // Solo para médicos, obtiene su registro de médico
        if ($user->tipo_usuario === 'M') {
            $medico = Medico::where('usuario_id', $user->id)->first();
            return inertia('HorariosAtencion/Create', [
                'medico_id' => $medico->id,
            ]);
        }

        // Admin puede elegir médico si lo necesitas (ajusta según tu lógica)
        $medicos = Medico::with('usuario')->get();
        return inertia('HorariosAtencion/Create', [
            'medicos' => $medicos
        ]);
    }

    public function store(HorarioAtencionRequest $request)
    {
        $user = Auth::user();

        // Si es médico, fuerza su propio medico_id
        if ($user->tipo_usuario === 'M') {
            $medico = Medico::where('usuario_id', $user->id)->first();
            $data = $request->validated();
            $data['medico_id'] = $medico->id;
            HorarioAtencion::create($data);
        } else {
            // Admin puede registrar cualquier médico
            HorarioAtencion::create($request->validated());
        }

        return redirect()->route('horarios-atencion.index')->with('success', 'Horario registrado correctamente.');
    }

    public function edit(HorarioAtencion $horario_atencion)
    {
        $user = Auth::user();
        $medico = null;

        if ($user->tipo_usuario === 'M') {
            $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
            // Solo puede editar sus propios horarios
            if ($horario_atencion->medico_id !== $medico->id) {
                abort(403, 'No autorizado para editar este horario');
            }
        }

        // Cargar la relación médico para la vista
        $horario_atencion->load('medico.usuario');

        return inertia('HorariosAtencion/Edit', [
            'horario' => $horario_atencion,
            'auth' => [
                'user' => array_merge($user->toArray(), [
                    'medico' => $user->tipo_usuario === 'M' ? $medico : null
                ])
            ]
        ]);
    }

    public function update(HorarioAtencionRequest $request, HorarioAtencion $horario_atencion)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($user->tipo_usuario === 'M') {
            $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
            
            // Asegurarse de que el médico solo pueda actualizar sus propios horarios
            if ($horario_atencion->medico_id !== $medico->id) {
                abort(403, 'No autorizado para actualizar este horario');
            }
            
            // Forzar el medico_id para prevenir cambios no autorizados
            $data['medico_id'] = $medico->id;
        }

        $horario_atencion->update($data);
        return redirect()->route('horarios-atencion.index')
            ->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(HorarioAtencion $horario_atencion)
    {
        $user = Auth::user();

        if ($user->tipo_usuario === 'M') {
            $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
            
            // Asegurarse de que el médico solo pueda eliminar sus propios horarios
            if ($horario_atencion->medico_id !== $medico->id) {
                return response()->json([
                    'message' => 'No autorizado para eliminar este horario'
                ], 403);
            }
        }

        $horario_atencion->delete();
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Horario eliminado correctamente.'
            ]);
        }
        
        return redirect()->route('horarios-atencion.index')
            ->with('success', 'Horario eliminado correctamente.');
    }

    /**
     * Muestra los horarios del médico autenticado
     */
    public function misHorarios()
    {
        $user = Auth::user();
        $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
        
        $horarios = HorarioAtencion::where('medico_id', $medico->id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->paginate(10);
            
        return inertia('HorariosAtencion/Index', [
            'horarios' => $horarios,
            'esVistaMedico' => true,
            'auth' => [
                'user' => array_merge($user->toArray(), [
                    'medico' => $medico
                ])
            ]
        ]);
    }
    
    /**
     * Muestra el formulario para que un médico cree un nuevo horario
     */
    public function crearHorarioMedico()
    {
        $user = Auth::user();
        $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
        
        return inertia('HorariosAtencion/Create', [
            'medico_id' => $medico->id,
            'esVistaMedico' => true,
            'auth' => [
                'user' => array_merge($user->toArray(), [
                    'medico' => $medico
                ])
            ]
        ]);
    }
    
    /**
     * Almacena un nuevo horario creado por un médico
     */
    public function guardarHorarioMedico(HorarioAtencionRequest $request)
    {
        $user = Auth::user();
        $medico = Medico::where('usuario_id', $user->id)->firstOrFail();
        
        $data = $request->validated();
        $data['medico_id'] = $medico->id; // Asegurar que el horario se asocie al médico correcto
        
        HorarioAtencion::create($data);
        
        return redirect()->route('medico.horarios.index')
            ->with('success', 'Horario creado correctamente.');
    }
}
