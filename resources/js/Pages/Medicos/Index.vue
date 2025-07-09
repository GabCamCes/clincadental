<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ medicos: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Médicos</h1>
            <Link :href="route('medicos.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Médico</Link>
        </div>
        <div v-if="medicos && medicos.data && medicos.data.length === 0" class="p-4 text-gray-600">No hay médicos registrados.</div>
        <table v-else-if="medicos && medicos.data && medicos.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-3">CI</th>
                    <th class="py-2 px-3">Nombre Completo</th>
                    <th class="py-2 px-3">Correo</th>
                    <th class="py-2 px-3">Especialidad</th>
                    <th class="py-2 px-3">Edad</th>
                    <th class="py-2 px-3">Género</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="m in medicos.data" :key="m.id" class="border-b hover:bg-gray-50">
                    <td class="py-2 px-3">{{ m.usuario.ci }}</td>
                    <td class="py-2 px-3">{{ m.usuario.nombres }} {{ m.usuario.apellido_paterno }} {{ m.usuario.apellido_materno }}</td>
                    <td class="py-2 px-3">{{ m.usuario.correo }}</td>
                    <td class="py-2 px-3">{{ m.especialidad?.nombre || 'Sin especialidad' }}</td>
                    <td class="py-2 px-3">{{ m.usuario.edad }}</td>
                    <td class="py-2 px-3">{{ m.usuario.genero === 'M' ? 'Masculino' : 'Femenino' }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('medicos.edit', m.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('medicos.destroy', m.id)" method="post" @submit.prevent="$inertia.delete(route('medicos.destroy', m.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar médico?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="medicos && medicos.links && medicos.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in medicos.links" :key="idx">
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
