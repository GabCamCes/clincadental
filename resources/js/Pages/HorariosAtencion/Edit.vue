<script setup>
import { useForm } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'

const props = defineProps({
    horario: Object
})

const form = useForm({
    dia_semana: props.horario.dia_semana,
    hora_inicio: props.horario.hora_inicio,
    hora_fin: props.horario.hora_fin
})

function submit() {
    form.put(route('horarios-atencion.update', props.horario.id))
}
</script>

<template>
    <BackToDashboard />
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded shadow">
        <h1 class="text-xl font-bold mb-4">Editar Horario de Atención</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block mb-1">Día de la semana</label>
                <select v-model="form.dia_semana" required class="w-full border rounded p-2">
                    <option>Lunes</option>
                    <option>Martes</option>
                    <option>Miércoles</option>
                    <option>Jueves</option>
                    <option>Viernes</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Hora de inicio</label>
                <input v-model="form.hora_inicio" type="time" required class="w-full border rounded p-2" />
            </div>
            <div class="mb-4">
                <label class="block mb-1">Hora de fin</label>
                <input v-model="form.hora_fin" type="time" required class="w-full border rounded p-2" />
            </div>
            <button :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Actualizar
            </button>
        </form>
    </div>
    <footer
        class="mt-auto text-center py-2 bg-gray-100 text-gray-700 dark:bg-[#232323] dark:text-gray-300 border-t border-gray-200 dark:border-gray-800"
        >
        <span class="text-sm">
            Visitas a esta página: <b>{{ $page.props.contador_visitas }}</b>
        </span>
    </footer>
</template>

