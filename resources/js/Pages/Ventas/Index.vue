<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'

const props = defineProps({
  ventas: Object,
})
</script>

<template>
  <BackToDashboard />
  <div class="max-w-5xl mx-auto mt-10">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Ventas</h1>
      <Link :href="route('ventas.create')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Nueva Venta
      </Link>
    </div>

    <div v-if="ventas.data.length === 0" class="p-4 text-gray-600">
      No hay ventas registradas.
    </div>

    <table v-else class="w-full bg-white dark:bg-slate-800 rounded shadow mb-6">
      <thead>
        <tr class="bg-gray-100 dark:bg-slate-700 text-left">
          <th class="py-2 px-3">Fecha</th>
          <th class="py-2 px-3">Paciente</th>
          <th class="py-2 px-3">Total</th>
          <th class="py-2 px-3">Método de Pago</th>
          <th class="py-2 px-3 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="v in ventas.data" :key="v.id" class="border-b hover:bg-gray-50">
          <td class="py-2 px-3">{{ v.fecha_venta }}</td>
          <td class="py-2 px-3">{{ v.paciente?.usuario?.nombres }} {{ v.paciente?.usuario?.apellido_paterno }}</td>
          <td class="py-2 px-3">Bs {{ Number(v.total).toFixed(2) }}</td>
          <td class="py-2 px-3">{{ v.metodo_pago }}</td>
          <td class="py-2 px-3 text-center flex gap-2 justify-center">
            <Link :href="route('ventas.edit', v.id)" class="btn btn-edit">Editar</Link>
            <form :action="route('ventas.destroy', v.id)" method="post" @submit.prevent="$inertia.delete(route('ventas.destroy', v.id))" class="inline">
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Paginación -->
    <div v-if="ventas.link && Array.isArray(ventas.link) && ventas.links.length > 0" class="flex justify-center gap-2">
      <Link
        v-for="link in ventas.link"
        :key="link.label"
        v-if="link.url !== null && typeof link.url !== 'undefined'"
        :href="link.url"
        :class="[
          'px-3 py-1 rounded',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100'
        ]"
        v-html="link.label"
      />
    </div>
  </div>
  <footer
        class="mt-auto text-center py-2 bg-gray-100 text-gray-700 dark:bg-[#232323] dark:text-gray-300 border-t border-gray-200 dark:border-gray-800"
        >
        <span class="text-sm">
            Visitas a esta página: <b>{{ $page.props.contador_visitas }}</b>
        </span>
  </footer>
</template>
