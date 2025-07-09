<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ especialidad: Object })
const form = useForm({ nombre: props.especialidad.nombre })
function submit() { form.put(route('especialidades.update', props.especialidad.id)) }
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-md mx-auto mt-10 bg-white shadow rounded px-8 py-6">
        <h2 class="text-xl font-bold mb-6">Editar Especialidad</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block mb-1">Nombre</label>
                <input v-model="form.nombre" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">Guardar</button>
                <Link :href="route('especialidades.index')" class="text-gray-700 underline ml-4">Cancelar</Link>
            </div>
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
