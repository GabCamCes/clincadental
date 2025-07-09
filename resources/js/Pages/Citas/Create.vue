<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    pacientes: {
        type: Array,
        default: () => []
    },
    medicos: {
        type: Array,
        required: true
    },
    servicios: {
        type: Array,
        required: true
    },
    salas: {
        type: Array,
        required: true
    },
    paciente_actual: {
        type: Object,
        default: null
    },
    es_paciente: {
        type: Boolean,
        default: false
    }
})

const form = useForm({
    paciente_id: props.paciente_actual?.id || '',
    medico_id: '',
    servicio_id: '',
    sala_id: '',
    fecha: '',
    hora: '',
    estado: 'pendiente',
    observaciones: '',
})

function submit() {
    form.post(route('citas.store'))
}
function nombreCompleto(usuario) {
    if (!usuario) return 'Usuario no disponible';
    const nombre = usuario.nombres || '';
    const apellidoPaterno = usuario.apellido_paterno || '';
    const apellidoMaterno = usuario.apellido_materno || '';
    return `${nombre} ${apellidoPaterno} ${apellidoMaterno}`.trim() || 'Nombre no disponible';
}
</script>
<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-lg mx-auto mt-10 bg-white shadow rounded px-8 py-6">
    <h2 class="text-xl font-bold mb-6">Agendar Cita</h2>
    <form @submit.prevent="submit">
      <!-- Mostrar información del paciente si es paciente -->
      <div v-if="props.es_paciente" class="mb-4 p-4 bg-gray-50 rounded-lg">
        <h3 class="font-medium text-gray-900 mb-2">Datos del Paciente</h3>
        <div class="grid grid-cols-2 gap-2">
          <div>
            <span class="text-sm text-gray-600">Nombre completo:</span>
            <p class="font-medium">{{ nombreCompleto(props.paciente_actual.usuario) }}</p>
          </div>
          <div>
            <span class="text-sm text-gray-600">Cédula de identidad:</span>
            <p class="font-mono">{{ props.paciente_actual.ci || 'No especificada' }}</p>
          </div>
        </div>
        <input type="hidden" v-model="form.paciente_id" />
      </div>
      
      <!-- Mostrar selector de paciente si es admin/médico -->
      <div v-else class="mb-4">
        <label class="block mb-1">Paciente</label>
        <select v-model="form.paciente_id" class="w-full border px-3 py-2 rounded" required>
          <option value="">Seleccione un paciente...</option>
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
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" :disabled="form.processing">Agendar</button>
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
