<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ salas: Object })
</script>

<template>
<AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-3xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Salas</h1>
            <Link :href="route('salas.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nueva Sala</Link>
        </div>
        <div v-if="salas && salas.data && salas.data.length === 0" class="p-4 text-gray-600">No hay salas registradas.</div>
        <table v-else-if="salas && salas.data && salas.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    
                    <th class="py-2 px-3">Nombre</th>
                    <th class="py-2 px-3">Descripción</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="sala in salas.data" :key="sala.id" class="border-b hover:bg-gray-50">
                    
                    <td class="py-2 px-3">{{ sala.nombre }}</td>
                    <td class="py-2 px-3">{{ sala.descripcion }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('salas.edit', sala.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('salas.destroy', sala.id)" method="post" @submit.prevent="$inertia.delete(route('salas.destroy', sala.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar sala?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="salas && salas.links && salas.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in salas.links" :key="idx">
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
