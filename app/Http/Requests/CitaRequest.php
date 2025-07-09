<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Cita;
use App\Models\HorarioAtencion;

class CitaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'paciente_id'   => 'required|exists:pacientes,id',
            'medico_id'     => 'required|exists:medicos,id',
            'servicio_id'   => 'required|exists:servicios,id',
            'sala_id'       => 'required|exists:salas,id',
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'estado'        => 'required|in:pendiente,atendida,cancelada',
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $v) {
            $fecha = $this->input('fecha');
            $hora  = $this->input('hora');
            $medico_id = $this->input('medico_id');

            // --- Valida horario de atención ---
            $diaSemana = $this->diaSemanaEs(date('N', strtotime($fecha))); // Devuelve Lunes, Martes, etc.

            $horarioDisponible = HorarioAtencion::where('medico_id', $medico_id)
                ->where('dia_semana', $diaSemana)
                ->where('hora_inicio', '<=', $hora)
                ->where('hora_fin', '>', $hora)
                ->exists();

            if (!$horarioDisponible) {
                $v->errors()->add('hora', 'El médico no tiene horario disponible para ese día y hora.');
            }

            // Resto de validaciones: sala, médico, paciente no ocupados...
            // (Puedes copiar aquí lo que ya tenías para evitar doble reserva)
            $sala_id = $this->input('sala_id');
            $citaId = $this->route('cita') ? $this->route('cita')->id : null;

            $sala_ocupada = Cita::where('sala_id', $sala_id)
                ->where('fecha', $fecha)
                ->where('hora', $hora)
                ->when($citaId, fn($q) => $q->where('id', '!=', $citaId))
                ->exists();

            if ($sala_ocupada) {
                $v->errors()->add('sala_id', 'La sala ya está ocupada en esa fecha y hora.');
            }

            $medico_ocupado = Cita::where('medico_id', $medico_id)
                ->where('fecha', $fecha)
                ->where('hora', $hora)
                ->when($citaId, fn($q) => $q->where('id', '!=', $citaId))
                ->exists();

            if ($medico_ocupado) {
                $v->errors()->add('medico_id', 'El médico ya tiene una cita a esa hora.');
            }

            $paciente_id = $this->input('paciente_id');
            $paciente_ocupado = Cita::where('paciente_id', $paciente_id)
                ->where('fecha', $fecha)
                ->where('hora', $hora)
                ->when($citaId, fn($q) => $q->where('id', '!=', $citaId))
                ->exists();

            if ($paciente_ocupado) {
                $v->errors()->add('paciente_id', 'El paciente ya tiene una cita a esa hora.');
            }
        });
    }

    // Traductor de número a día de semana en español compatible con tu tabla
    private function diaSemanaEs($n)
    {
        // ISO-8601: 1=Lunes ... 5=Viernes
        $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];
        return $dias[$n] ?? 'Lunes';
    }
}

