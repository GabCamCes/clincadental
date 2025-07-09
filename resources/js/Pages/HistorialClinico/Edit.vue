<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  historial: Object
})

const form = useForm({
  diagnostico: props.historial.diagnostico,
  tratamiento: props.historial.tratamiento,
  observaciones: props.historial.observaciones ?? '',
})

function submit() {
  form.put(route('historial-clinico.update', props.historial.id))
}
</script>

<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Editar Historial Clínico</h1>
    <div class="mb-4 bg-gray-50 rounded p-3">
      <b>Cita:</b> [#{{ historial.cita_id }}] 
      {{ historial.cita?.paciente?.usuario?.nombres }} -
      {{ historial.cita?.medico?.usuario?.nombres }} -
      {{ historial.cita?.servicio?.nombre }} 
      <span class="text-gray-500 text-xs">({{ historial.cita?.fecha }} {{ historial.cita?.hora }})</span>
    </div>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block mb-1">Diagnóstico:</label>
        <textarea v-model="form.diagnostico" class="w-full border rounded p-2"></textarea>
        <span v-if="form.errors.diagnostico" class="text-red-500 text-sm">{{ form.errors.diagnostico }}</span>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Tratamiento:</label>
        <textarea v-model="form.tratamiento" class="w-full border rounded p-2"></textarea>
        <span v-if="form.errors.tratamiento" class="text-red-500 text-sm">{{ form.errors.tratamiento }}</span>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Observaciones:</label>
        <textarea v-model="form.observaciones" class="w-full border rounded p-2"></textarea>
        <span v-if="form.errors.observaciones" class="text-red-500 text-sm">{{ form.errors.observaciones }}</span>
      </div>
      <div class="flex gap-2">
        <button type="submit" :disabled="form.processing" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Actualizar</button>
        <Link :href="route('historial-clinico.index')" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Volver</Link>
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
ss