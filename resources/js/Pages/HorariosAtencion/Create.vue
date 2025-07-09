<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    medico_id: { type: [Number, String], default: null }, // Solo para médico autenticado
    medicos: { type: Array, default: () => [] } // Solo para admin
})

const form = useForm({
    medico_id: props.medico_id ?? '', // Si es médico autenticado, ya viene; si es admin, debe elegir
    dia_semana: '',
    hora_inicio: '',
    hora_fin: ''
})

function submit() {
    form.post(route('horarios-atencion.store'))
}
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-lg mx-auto mt-10 p-6 bg-white rounded shadow">
        <h1 class="text-xl font-bold mb-4">Registrar Horario de Atención</h1>
        <form @submit.prevent="submit">
            <!-- Si el usuario es admin, muestra select de médicos -->
            <div v-if="!props.medico_id" class="mb-4">
                <label class="block mb-1">Médico</label>
                <select v-model="form.medico_id" required class="w-full border rounded p-2">
                    <option value="">Seleccione...</option>
                    <option v-for="medico in props.medicos" :value="medico.id" :key="medico.id">
                        {{ medico.usuario.nombres }} {{ medico.usuario.apellido_paterno }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Día de la semana</label>
                <select v-model="form.dia_semana" required class="w-full border rounded p-2">
                    <option value="">Seleccione...</option>
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
                Guardar
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
    </AuthenticatedLayout>
</template>

