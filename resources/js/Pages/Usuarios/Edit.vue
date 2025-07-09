<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
const props = defineProps({
    usuario: Object,
})

const form = useForm({
    ci: props.usuario.ci,
    nombres: props.usuario.nombres,
    apellido_paterno: props.usuario.apellido_paterno,
    apellido_materno: props.usuario.apellido_materno,
    telefono: props.usuario.telefono,
    correo: props.usuario.correo,
    edad: props.usuario.edad,
    genero: props.usuario.genero,
    tipo_usuario: props.usuario.tipo_usuario,
    password: '',
})

function submit() {
    form.put(route('usuarios.update', props.usuario.id))
}
</script>

<template>
    <BackToDashboard />
    <div class="max-w-lg mx-auto mt-10 bg-white shadow rounded px-8 py-6">
        <h2 class="text-xl font-bold mb-6">Editar usuario</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block mb-1">CI</label>
                <input v-model="form.ci" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.ci" class="text-red-500 text-sm">{{ form.errors.ci }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Nombres</label>
                <input v-model="form.nombres" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.nombres" class="text-red-500 text-sm">{{ form.errors.nombres }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Apellido paterno</label>
                <input v-model="form.apellido_paterno" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.apellido_paterno" class="text-red-500 text-sm">{{ form.errors.apellido_paterno }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Apellido materno</label>
                <input v-model="form.apellido_materno" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.apellido_materno" class="text-red-500 text-sm">{{ form.errors.apellido_materno }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Teléfono</label>
                <input v-model="form.telefono" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.telefono" class="text-red-500 text-sm">{{ form.errors.telefono }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Correo</label>
                <input v-model="form.correo" type="email" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.correo" class="text-red-500 text-sm">{{ form.errors.correo }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Edad</label>
                <input v-model="form.edad" type="number" min="0" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.edad" class="text-red-500 text-sm">{{ form.errors.edad }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Género</label>
                <select v-model="form.genero" class="w-full border px-3 py-2 rounded">
                    <option value="">Seleccione...</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
                <div v-if="form.errors.genero" class="text-red-500 text-sm">{{ form.errors.genero }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Tipo de usuario</label>
                <select v-model="form.tipo_usuario" class="w-full border px-3 py-2 rounded">
                    <option value="">Seleccione...</option>
                    <option value="A">Administrador</option>
                    <option value="M">Médico</option>
                    <option value="P">Paciente</option>
                </select>
                <div v-if="form.errors.tipo_usuario" class="text-red-500 text-sm">{{ form.errors.tipo_usuario }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Contraseña (dejar vacío para no cambiar)</label>
                <input v-model="form.password" type="password" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">
                    Guardar cambios
                </button>
                <Link :href="route('usuarios.index')" class="text-gray-700 underline ml-4">Cancelar</Link>
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
</template>
