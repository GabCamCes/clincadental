<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ servicios: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Servicios</h1>
            <Link :href="route('servicios.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Servicio</Link>
        </div>
        <div v-if="servicios && servicios.data && servicios.data.length === 0" class="p-4 text-gray-600">No hay servicios registrados.</div>
        <table v-else-if="servicios && servicios.data && servicios.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    
                    <th class="py-2 px-3">Nombre</th>
                    <th class="py-2 px-3">Descripción</th>
                    <th class="py-2 px-3">Precio</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="servicio in servicios.data" :key="servicio.id" class="border-b hover:bg-gray-50">
                    
                    <td class="py-2 px-3">{{ servicio.nombre }}</td>
                    <td class="py-2 px-3">{{ servicio.descripcion }}</td>
                    <td class="py-2 px-3">Bs {{ Number(servicio.precio).toFixed(2) }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('servicios.edit', servicio.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('servicios.destroy', servicio.id)" method="post" @submit.prevent="$inertia.delete(route('servicios.destroy', servicio.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar servicio?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="servicios && servicios.links && servicios.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in servicios.links" :key="idx">
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
    
  </AuthenticatedLayout>
</template>
