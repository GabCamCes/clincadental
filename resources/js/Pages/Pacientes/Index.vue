<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ pacientes: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Pacientes</h1>
            <Link :href="route('pacientes.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Paciente</Link>
        </div>
        <div v-if="pacientes && pacientes.data && pacientes.data.length === 0" class="p-4 text-gray-600">No hay pacientes registrados.</div>
        <table v-else-if="pacientes && pacientes.data && pacientes.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-3">CI</th>
                    <th class="py-2 px-3">Nombre Completo</th>
                    <th class="py-2 px-3">Correo</th>
                    <th class="py-2 px-3">Edad</th>
                    <th class="py-2 px-3">Género</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in pacientes.data" :key="p.id" class="border-b hover:bg-gray-50">
                    <td class="py-2 px-3">{{ p.usuario.ci }}</td>
                    <td class="py-2 px-3">{{ p.usuario.nombres }} {{ p.usuario.apellido_paterno }} {{ p.usuario.apellido_materno }}</td>
                    <td class="py-2 px-3">{{ p.usuario.correo }}</td>
                    <td class="py-2 px-3">{{ p.usuario.edad }}</td>
                    <td class="py-2 px-3">{{ p.usuario.genero === 'M' ? 'Masculino' : 'Femenino' }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('pacientes.edit', p.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('pacientes.destroy', p.id)" method="post" @submit.prevent="$inertia.delete(route('pacientes.destroy', p.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar paciente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="pacientes && pacientes.links && pacientes.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in pacientes.links" :key="idx">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="['px-3 py-1 rounded', link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100']"
                    v-html="link.label"
                />
                <span
                    v-else
                    :class="['px-3 py-1 rounded text-gray-400 select-none', link.active ? 'bg-blue-600 text-white' : 'bg-gray-100']"
                    v-html="link.label"
                />
            </template>
        </div>
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
