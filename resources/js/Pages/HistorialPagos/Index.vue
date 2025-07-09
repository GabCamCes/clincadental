<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage, router } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const props = defineProps({
  historial: Object
})
</script>

<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-5xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Historial de Pagos</h1>

    <table class="w-full bg-white rounded shadow mb-6">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-3">Fecha</th>
          <th class="py-2 px-3">Pago</th>
          <th class="py-2 px-3">Usuario</th>
          <th class="py-2 px-3">Acción</th>
          <th class="py-2 px-3">Monto</th>
          <th class="py-2 px-3">Tipo de Pago</th>
          <th class="py-2 px-3">Observaciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="h in historial.data" :key="h.id" class="border-b hover:bg-gray-50">
          <td class="py-2 px-3">{{ h.fecha_evento }}</td>
          <td class="py-2 px-3">#{{ h.pago_id }}</td>
          <td class="py-2 px-3">
            {{ h.usuario?.nombres }} {{ h.usuario?.apellido_paterno }}
          </td>
          <td class="py-2 px-3">{{ h.accion }}</td>
          <td class="py-2 px-3">Bs {{ Number(h.monto).toFixed(2) }}</td>
          <td class="py-2 px-3">{{ h.tipo_pago }}</td>
          <td class="py-2 px-3">{{ h.observaciones }}</td>
        </tr>
      </tbody>
    </table>

    <div v-if="historial.link && Array.isArray(historial.link) && historial.link.length > 0" class="flex justify-center gap-2">
      <Link
        v-for="link in historial.link"
        :key="link.label"
        v-if="link.url"
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
  </AuthenticatedLayout>
</template>
