<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    productos: Array,
    servicios: Array,
})
const form = useForm({
    titulo: '',
    descripcion: '',
    tipo_promocion: '',
    porcentaje_descuento: '',
    fecha_inicio: '',
    fecha_fin: '',
    estado: 'Activa',
    productos: [],
    servicios: [],
})
function submit() {
    form.post(route('promociones.store'))
}
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-md mx-auto mt-10 bg-white shadow rounded px-8 py-6">
        <h2 class="text-xl font-bold mb-6">Nueva Promoción</h2>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block mb-1">Título</label>
                <input v-model="form.titulo" type="text" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.titulo" class="text-red-500 text-sm">{{ form.errors.titulo }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Descripción</label>
                <textarea v-model="form.descripcion" class="w-full border px-3 py-2 rounded"></textarea>
                <div v-if="form.errors.descripcion" class="text-red-500 text-sm">{{ form.errors.descripcion }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Tipo de Promoción</label>
                <select v-model="form.tipo_promocion" class="w-full border px-3 py-2 rounded">
                    <option value="">Seleccione...</option>
                    <option value="Producto">Producto</option>
                    <option value="Servicio">Servicio</option>
                </select>
                <div v-if="form.errors.tipo_promocion" class="text-red-500 text-sm">{{ form.errors.tipo_promocion }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">% Descuento</label>
                <input v-model="form.porcentaje_descuento" type="number" min="0" max="100" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.porcentaje_descuento" class="text-red-500 text-sm">{{ form.errors.porcentaje_descuento }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Fecha Inicio</label>
                <input v-model="form.fecha_inicio" type="date" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.fecha_inicio" class="text-red-500 text-sm">{{ form.errors.fecha_inicio }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Fecha Fin</label>
                <input v-model="form.fecha_fin" type="date" class="w-full border px-3 py-2 rounded" />
                <div v-if="form.errors.fecha_fin" class="text-red-500 text-sm">{{ form.errors.fecha_fin }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Estado</label>
                <select v-model="form.estado" class="w-full border px-3 py-2 rounded">
                    <option value="Activa">Activa</option>
                    <option value="Inactiva">Inactiva</option>
                </select>
                <div v-if="form.errors.estado" class="text-red-500 text-sm">{{ form.errors.estado }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Productos asociados</label>
                <select v-model="form.productos" multiple class="w-full border px-3 py-2 rounded">
                    <option v-for="prod in props.productos" :key="prod.id" :value="prod.id">{{ prod.nombre }}</option>
                </select>
                <div v-if="form.errors.productos" class="text-red-500 text-sm">{{ form.errors.productos }}</div>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Servicios asociados</label>
                <select v-model="form.servicios" multiple class="w-full border px-3 py-2 rounded">
                    <option v-for="ser in props.servicios" :key="ser.id" :value="ser.id">{{ ser.nombre }}</option>
                </select>
                <div v-if="form.errors.servicios" class="text-red-500 text-sm">{{ form.errors.servicios }}</div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">Crear</button>
                <Link :href="route('promociones.index')" class="text-gray-700 underline ml-4">Cancelar</Link>
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

