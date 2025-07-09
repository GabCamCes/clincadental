<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({ productos: Object })
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Productos</h1>
            <Link :href="route('productos.create')" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Producto</Link>
        </div>
        <div v-if="productos && productos.data && productos.data.length === 0" class="p-4 text-gray-600">No hay productos registrados.</div>
        <table v-else-if="productos && productos.data && productos.data.length > 0" class="w-full bg-white rounded shadow mb-6">
            <thead>
                <tr class="bg-gray-100 text-left">
                    
                    <th class="py-2 px-3">Nombre</th>
                    <th class="py-2 px-3">Descripción</th>
                    <th class="py-2 px-3">Precio</th>
                    <th class="py-2 px-3">Stock</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="producto in productos.data" :key="producto.id" class="border-b hover:bg-gray-50">
                    
                    <td class="py-2 px-3">{{ producto.nombre }}</td>
                    <td class="py-2 px-3">{{ producto.descripcion }}</td>
                    <td class="py-2 px-3">Bs {{ Number(producto.precio).toFixed(2) }}</td>
                    <td class="py-2 px-3">{{ producto.stock }}</td>
                    <td class="py-2 px-3 text-center flex gap-2 justify-center">
                        <Link :href="route('productos.edit', producto.id)" class="text-blue-600 underline">Editar</Link>
                        <form :action="route('productos.destroy', producto.id)" method="post" @submit.prevent="$inertia.delete(route('productos.destroy', producto.id))" class="inline">
                            <button type="submit" class="text-red-600 underline" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-if="productos && productos.links && productos.links.length > 0" class="flex justify-center gap-2 mt-4">
            <template v-for="(link, idx) in productos.links" :key="idx">
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
