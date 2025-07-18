<script setup>

function translatePaginationLabel(label) {
  if (!label) return '';
  if (label.includes('Previous') || label === 'pagination.previous' || label.includes('«')) return 'Página anterior';
  if (label.includes('Next') || label === 'pagination.next' || label.includes('»')) return 'Página siguiente';
  return label.replace(/&laquo;|&raquo;/g, '').trim();
}
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ especialidades: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-3xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Especialidades</h1>
            <Link :href="route('especialidades.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nueva</Link>
        </div>
        <div v-if="especialidades && especialidades.data && especialidades.data.length === 0" class="p-4 text-gray-600">No hay especialidades.</div>
        <table v-else-if="especialidades && especialidades.data && especialidades.data.length > 0" class="w-full bg-white dark:bg-slate-800 rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 dark:bg-slate-700 text-left">
                    
                    <th class="py-2 px-3">Nombre</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="esp in especialidades.data" :key="esp.id" class="border-b hover:bg-gray-50">
                    
                    <td class="py-2 px-3">{{ esp.nombre }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('especialidades.edit', esp.id)" class="btn btn-edit">Editar</Link>
                        <form :action="route('especialidades.destroy', esp.id)" method="post" @submit.prevent="$inertia.delete(route('especialidades.destroy', esp.id))" class="inline">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="especialidades && especialidades.links && especialidades.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in especialidades.links" :key="idx">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="['px-3 py-1 rounded', link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 hover:bg-blue-200 dark:hover:bg-slate-600']"
                    v-html="translatePaginationLabel(link.label)"
                />
                <span
                    v-else
                    :class="['px-3 py-1 rounded text-gray-400 select-none', link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-700 dark:text-gray-200']"
                    v-html="translatePaginationLabel(link.label)"
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
