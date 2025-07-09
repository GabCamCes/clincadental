<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  ventas: Array
})

const form = useForm({
  venta_id: '',
  fecha_pago: new Date().toISOString().substr(0, 10),
  monto_pagado: '',
  metodo_pago: '',
  estado_pago: '',
})

function submit() {
  form.post(route('pagos.store'))
}
</script>

<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Nuevo Pago</h1>
    <form @submit.prevent="submit">

      <div class="mb-4">
        <label class="block mb-1">Venta:</label>
        <select v-model="form.venta_id" class="w-full border rounded p-2">
          <option value="">Selecciona la venta</option>
          <option v-for="v in ventas" :key="v.id" :value="v.id">
            #{{ v.id }} - Paciente: {{ v.paciente?.usuario?.nombres }} {{ v.paciente?.usuario?.apellido_paterno }} - Total: Bs {{ Number(v.total).toFixed(2) }}
          </option>
        </select>
        <span v-if="form.errors.venta_id" class="text-red-500 text-sm">{{ form.errors.venta_id }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Fecha de pago:</label>
        <input type="date" v-model="form.fecha_pago" class="w-full border rounded p-2"/>
        <span v-if="form.errors.fecha_pago" class="text-red-500 text-sm">{{ form.errors.fecha_pago }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Monto pagado (Bs):</label>
        <input type="number" min="0" v-model="form.monto_pagado" class="w-full border rounded p-2"/>
        <span v-if="form.errors.monto_pagado" class="text-red-500 text-sm">{{ form.errors.monto_pagado }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Método de pago:</label>
        <select v-model="form.metodo_pago" class="w-full border rounded p-2">
          <option value="">Selecciona método</option>
          <option value="Efectivo">Efectivo</option>
          <option value="Transferencia">Transferencia</option>
        </select>
        <span v-if="form.errors.metodo_pago" class="text-red-500 text-sm">{{ form.errors.metodo_pago }}</span>
      </div>

      <div class="mb-4">
        <label class="block mb-1">Estado:</label>
        <select v-model="form.estado_pago" class="w-full border rounded p-2">
          <option value="">Selecciona estado</option>
          <option value="Pendiente">Pendiente</option>
          <option value="Pagado">Pagado</option>
          <option value="Rechazado">Rechazado</option>
        </select>
        <span v-if="form.errors.estado_pago" class="text-red-500 text-sm">{{ form.errors.estado_pago }}</span>
      </div>

      <div class="flex gap-2">
        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
        <Link :href="route('pagos.index')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Volver</Link>
      </div>
    </form>
  </div>
  </AuthenticatedLayout>
</template>
