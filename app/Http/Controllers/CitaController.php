<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Http\Requests\CitaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PacienteController;
use App\Models\Paciente;


class CitaController extends Controller
{
    public function index()
    {
    $user = \Auth::user();

    // Si es paciente, filtra SOLO sus citas
    if ($user->tipo_usuario === 'P') {
        $paciente = \App\Models\Paciente::where('usuario_id', $user->id)->first();
        $citas = \App\Models\Cita::with(['paciente.usuario', 'medico.usuario', 'servicio', 'sala'])
            ->where('paciente_id', $paciente->id)
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }
    // Si es médico, filtra sus citas
    else if ($user->tipo_usuario === 'M') {
        $medico = \App\Models\Medico::where('usuario_id', $user->id)->first();
        $citas = \App\Models\Cita::with(['paciente.usuario', 'medico.usuario', 'servicio', 'sala'])
            ->where('medico_id', $medico->id)
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }
    // Si es admin, todas las citas
    else {
        $citas = \App\Models\Cita::with(['paciente.usuario', 'medico.usuario', 'servicio', 'sala'])
            ->orderBy('fecha', 'desc')
            ->paginate(10);
    }

    return Inertia::render('Citas/Index', [
        'citas' => $citas,
        'tipo_usuario' => $user->tipo_usuario,
    ]);
}   

    public function create()
    {
        $user = Auth::user();
        
        // Si es paciente, obtenemos su información
        if ($user->tipo_usuario === 'P') {
            $paciente = Paciente::with('usuario')
                ->where('usuario_id', $user->id)
                ->firstOrFail();
                
            return Inertia::render('Citas/Create', [
                'paciente_actual' => $paciente, // Enviamos el paciente actual
                'pacientes' => [], // No enviamos lista de pacientes
                'medicos'   => \App\Models\Medico::with('usuario')->get(),
                'servicios' => \App\Models\Servicio::all(),
                'salas'     => \App\Models\Sala::all(),
                'es_paciente' => true
            ]);
        }

        // Si es médico o admin, lista todos los pacientes
        return Inertia::render('Citas/Create', [
            'paciente_actual' => null,
            'pacientes' => \App\Models\Paciente::with('usuario')->get(),
            'medicos'   => \App\Models\Medico::with('usuario')->get(),
            'servicios' => \App\Models\Servicio::all(),
            'salas'     => \App\Models\Sala::all(),
            'es_paciente' => false
        ]);
    }

    public function store(CitaRequest $request)
    {
    $data = $request->validated();
    $user = \Auth::user();

    if ($user->tipo_usuario === 'P') {
        $paciente = \App\Models\Paciente::where('usuario_id', $user->id)->first();
        $data['paciente_id'] = $paciente->id; // Siempre el suyo
    }
    Cita::create($data);
    return redirect()->route('citas.index')->with('success', 'Cita agendada correctamente.');
    }

    public function edit(Cita $cita)
    {
        return Inertia::render('Citas/Edit', [
            'cita'      => $cita,
            'pacientes' => \App\Models\Paciente::with('usuario')->get(),
            'medicos'   => \App\Models\Medico::with('usuario')->get(),
            'servicios' => \App\Models\Servicio::all(),
            'salas'     => \App\Models\Sala::all(),
        ]);
    }

    public function update(CitaRequest $request, Cita $cita)
    {
        $cita->update($request->validated());
        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}
