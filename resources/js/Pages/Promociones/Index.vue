<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ promociones: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Promociones</h1>
            <Link :href="route('promociones.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nueva Promoción</Link>
        </div>
        <div v-if="promociones && promociones.data && promociones.data.length === 0" class="p-4 text-gray-600">No hay promociones registradas.</div>
        <table v-else-if="promociones && promociones.data && promociones.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="py-2 px-3">Título</th>
                    <th class="py-2 px-3">Tipo</th>
                    <th class="py-2 px-3">% Descuento</th>
                    <th class="py-2 px-3">Vigencia</th>
                    <th class="py-2 px-3">Estado</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in promociones.data" :key="p.id" class="border-b hover:bg-gray-50">
                    <td class="py-2 px-3">{{ p.titulo }}</td>
                    <td class="py-2 px-3">{{ p.tipo_promocion }}</td>
                    <td class="py-2 px-3">{{ p.porcentaje_descuento }}%</td>
                    <td class="py-2 px-3">{{ p.fecha_inicio }}<br>- {{ p.fecha_fin }}</td>
                    <td class="py-2 px-3">{{ p.estado }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('promociones.edit', p.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('promociones.destroy', p.id)" method="post" @submit.prevent="$inertia.delete(route('promociones.destroy', p.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar promoción?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="promociones && promociones.links && promociones.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in promociones.links" :key="idx">
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
