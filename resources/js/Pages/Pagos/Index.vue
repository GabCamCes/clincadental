<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  pagos: Object
})
</script>

<template>
  <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Pagos</h1>
        <Link :href="route('pagos.create')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Nuevo Pago
        </Link>
      </div>

      <div v-if="!pagos.data || pagos.data.length === 0" class="p-4 text-gray-600">
        No hay pagos registrados.
      </div>

      <table v-else class="w-full bg-white dark:bg-slate-800 rounded shadow mb-6">
        <thead>
          <tr class="bg-gray-100 dark:bg-slate-700 text-left">
            <th class="py-2 px-3">Fecha</th>
            <th class="py-2 px-3">Paciente</th>
            <th class="py-2 px-3">Venta</th>
            <th class="py-2 px-3">Monto</th>
            <th class="py-2 px-3">Método</th>
            <th class="py-2 px-3">Estado</th>
            <th class="py-2 px-3 text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in pagos.data" :key="p.id" class="border-b hover:bg-gray-50">
            <td class="py-2 px-3">{{ p.fecha_pago ?? '-' }}</td>
            <td class="py-2 px-3">
              {{ p.venta?.paciente?.usuario?.nombres ?? '-' }}
              {{ p.venta?.paciente?.usuario?.apellido_paterno ?? '' }}
            </td>
            <td class="py-2 px-3">#{{ p.venta_id ?? '-' }}</td>
            <td class="py-2 px-3">Bs {{ Number(p.monto_pagado ?? 0).toFixed(2) }}</td>
            <td class="py-2 px-3">{{ p.metodo_pago ?? '-' }}</td>
            <td class="py-2 px-3">
              <span :class="{
                'text-yellow-600': p.estado_pago === 'Pendiente',
                'text-green-600': p.estado_pago === 'Pagado',
                'text-red-600': p.estado_pago === 'Rechazado'
              }">
                {{ p.estado_pago ?? '-' }}
              </span>
            </td>
            <td class="py-2 px-3 text-center flex gap-2 justify-center">
              <!-- Agrega aquí el botón para Ver QR -->
              <Link
                :href="route('pagos.show', p.id)"
                class="text-green-700 underline"
                v-if="p.estado_pago !== 'Rechazado'"
              >
                Ver QR
              </Link>
              <Link :href="route('pagos.edit', p.id)" class="btn btn-edit">Editar</Link>
              <form :action="route('pagos.destroy', p.id)" method="post" @submit.prevent="$inertia.delete(route('pagos.destroy', p.id))" class="inline">
                <button type="submit" class="btn btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Puedes dejar paginación aquí -->
    </div>
  </AuthenticatedLayout>
</template>


