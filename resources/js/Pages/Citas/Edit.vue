<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    cita: Object,
    pacientes: Array,
    medicos: Array,
    servicios: Array,
    salas: Array,
})
const form = useForm({
    paciente_id: props.cita.paciente_id,
    medico_id: props.cita.medico_id,
    servicio_id: props.cita.servicio_id,
    sala_id: props.cita.sala_id,
    fecha: props.cita.fecha,
    hora: props.cita.hora,
    estado: props.cita.estado,
    observaciones: props.cita.observaciones || '',
})
function submit() {
    form.put(route('citas.update', props.cita.id))
}
function nombreCompleto(usuario) {
    return `${usuario.nombres} ${usuario.apellido_paterno} ${usuario.apellido_materno}`
}
</script>
<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-lg mx-auto mt-10 bg-white shadow rounded px-8 py-6">
    <h2 class="text-xl font-bold mb-6">Editar Cita</h2>
    <form @submit.prevent="submit">
      <!-- igual que Create.vue, pero con valores por defecto -->
      <div class="mb-4">
        <label class="block mb-1">Paciente</label>
        <select v-model="form.paciente_id" class="w-full border px-3 py-2 rounded">
          <option value="">Seleccione...</option>
          <option v-for="p in props.pacientes" :key="p.id" :value="p.id">
            {{ nombreCompleto(p.usuario) }}
          </option>
        </select>
        <div v-if="form.errors.paciente_id" class="text-red-500 text-sm">{{ form.errors.paciente_id }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Médico</label>
        <select v-model="form.medico_id" class="w-full border px-3 py-2 rounded">
          <option value="">Seleccione...</option>
          <option v-for="m in props.medicos" :key="m.id" :value="m.id">
            {{ nombreCompleto(m.usuario) }}
          </option>
        </select>
        <div v-if="form.errors.medico_id" class="text-red-500 text-sm">{{ form.errors.medico_id }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Servicio</label>
        <select v-model="form.servicio_id" class="w-full border px-3 py-2 rounded">
          <option value="">Seleccione...</option>
          <option v-for="s in props.servicios" :key="s.id" :value="s.id">
            {{ s.nombre }}
          </option>
        </select>
        <div v-if="form.errors.servicio_id" class="text-red-500 text-sm">{{ form.errors.servicio_id }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Sala</label>
        <select v-model="form.sala_id" class="w-full border px-3 py-2 rounded">
          <option value="">Seleccione...</option>
          <option v-for="sa in props.salas" :key="sa.id" :value="sa.id">
            {{ sa.nombre }}
          </option>
        </select>
        <div v-if="form.errors.sala_id" class="text-red-500 text-sm">{{ form.errors.sala_id }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Fecha</label>
        <input v-model="form.fecha" type="date" class="w-full border px-3 py-2 rounded" />
        <div v-if="form.errors.fecha" class="text-red-500 text-sm">{{ form.errors.fecha }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Hora</label>
        <input v-model="form.hora" type="time" class="w-full border px-3 py-2 rounded" />
        <div v-if="form.errors.hora" class="text-red-500 text-sm">{{ form.errors.hora }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Estado</label>
        <select v-model="form.estado" class="w-full border px-3 py-2 rounded">
          <option value="pendiente">Pendiente</option>
          <option value="atendida">Atendida</option>
          <option value="cancelada">Cancelada</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Observaciones</label>
        <textarea v-model="form.observaciones" class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">Guardar</button>
        <Link :href="route('citas.index')" class="text-gray-700 underline ml-4">Cancelar</Link>
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
