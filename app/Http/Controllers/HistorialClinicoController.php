<?php

namespace App\Http\Controllers;

use App\Models\HistorialClinico;
use App\Models\Cita;
use App\Http\Requests\HistorialClinicoRequest;
use Inertia\Inertia;

class HistorialClinicoController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = HistorialClinico::with('cita.paciente.usuario', 'cita.medico.usuario', 'cita.servicio')
            ->orderBy('fecha_registro', 'desc');

        // Si es paciente, solo puede ver su propio historial
        if ($user->tipo_usuario === 'P') {
            // Verificar si el usuario tiene un perfil de paciente
            if (!$user->paciente) {
                return Inertia::render('HistorialClinico/Index', [
                    'historiales' => ['data' => []],
                    'permisos' => [
                        'ver_todo' => false,
                        'crear' => false,
                        'editar' => false,
                    ],
                    'error' => 'No tienes un perfil de paciente asociado a tu cuenta.'
                ]);
            }
            
            $query->whereHas('cita', function($q) use ($user) {
                $q->where('paciente_id', $user->paciente->id);
            });
        }
        // Si es médico, puede ver el historial de sus pacientes
        elseif ($user->tipo_usuario === 'M') {
            // Verificar si el usuario tiene un perfil de médico
            if (!$user->medico) {
                return Inertia::render('HistorialClinico/Index', [
                    'historiales' => ['data' => []],
                    'permisos' => [
                        'ver_todo' => false,
                        'crear' => false,
                        'editar' => false,
                    ],
                    'error' => 'No tienes un perfil de médico asociado a tu cuenta.'
                ]);
            }
            
            $query->whereHas('cita', function($q) use ($user) {
                $q->where('medico_id', $user->medico->id);
            });
        }
        // Admin puede ver todo el historial

        $historiales = $query->paginate(10);

        return Inertia::render('HistorialClinico/Index', [
            'historiales' => $historiales,
            'permisos' => [
                'ver_todo' => $user->tipo_usuario === 'A',
                'crear' => in_array($user->tipo_usuario, ['A', 'M']),
                'editar' => in_array($user->tipo_usuario, ['A', 'M']),
            ]
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        
        // Base query para citas atendidas sin historial
        $query = Cita::with('paciente.usuario', 'medico.usuario', 'servicio')
            ->where('estado', 'atendida')
            ->whereDoesntHave('historialClinico');
        
        // Si es médico, solo puede crear historiales de sus propias citas
        if ($user->tipo_usuario === 'M') {
            if (!$user->medico) {
                return redirect()->back()->with('error', 'No tienes un perfil de médico asociado.');
            }
            $query->where('medico_id', $user->medico->id);
        }
        
        $citas = $query->get();

        return Inertia::render('HistorialClinico/Create', [
            'citas' => $citas,
            'esVistaMedico' => $user->tipo_usuario === 'M'
        ]);
    }

    public function store(HistorialClinicoRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        
        // Verificar que la cita existe
        $cita = Cita::findOrFail($data['cita_id']);
        
        // Si es médico, verificar que la cita es suya
        if ($user->tipo_usuario === 'M') {
            if (!$user->medico || $cita->medico_id !== $user->medico->id) {
                return redirect()->back()->with('error', 'No estás autorizado para crear este historial clínico.');
            }
        }
        
        // Verificar que la cita no tenga ya un historial
        if ($cita->historialClinico) {
            return redirect()->back()->with('error', 'Esta cita ya tiene un historial clínico.');
        }
        
        $data['fecha_registro'] = now();
        HistorialClinico::create($data);

        $redirectRoute = $user->tipo_usuario === 'M' ? 'medico.dashboard' : 'historial-clinico.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Historial clínico registrado correctamente.');
    }

    public function edit(HistorialClinico $historial_clinico)
    {
        $user = auth()->user();
        
        // Cargar relaciones necesarias
        $historial_clinico->load('cita.paciente.usuario', 'cita.medico.usuario', 'cita.servicio');
        
        // Si es médico, verificar que el historial es de una de sus citas
        if ($user->tipo_usuario === 'M') {
            if (!$user->medico || $historial_clinico->cita->medico_id !== $user->medico->id) {
                abort(403, 'No estás autorizado para editar este historial clínico.');
            }
        }
        
        return Inertia::render('HistorialClinico/Edit', [
            'historial' => $historial_clinico,
            'esVistaMedico' => $user->tipo_usuario === 'M'
        ]);
    }

    public function update(HistorialClinicoRequest $request, HistorialClinico $historial_clinico)
    {
        $user = auth()->user();
        
        // Si es médico, verificar que el historial es de una de sus citas
        if ($user->tipo_usuario === 'M') {
            if (!$user->medico || $historial_clinico->cita->medico_id !== $user->medico->id) {
                abort(403, 'No estás autorizado para actualizar este historial clínico.');
            }
        }
        
        $historial_clinico->update($request->validated());
        
        $redirectRoute = $user->tipo_usuario === 'M' ? 'medico.dashboard' : 'historial-clinico.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Historial clínico actualizado correctamente.');
    }

    public function destroy(HistorialClinico $historial_clinico)
    {
        $user = auth()->user();
        
        // Si es médico, verificar que el historial es de una de sus citas
        if ($user->tipo_usuario === 'M') {
            if (!$user->medico || $historial_clinico->cita->medico_id !== $user->medico->id) {
                abort(403, 'No estás autorizado para eliminar este historial clínico.');
            }
        }
        
        $historial_clinico->delete();
        
        $redirectRoute = $user->tipo_usuario === 'M' ? 'medico.dashboard' : 'historial-clinico.index';
        return redirect()->route($redirectRoute)
            ->with('success', 'Historial clínico eliminado correctamente.');
    }
}
