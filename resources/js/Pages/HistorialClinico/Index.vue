<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  historiales: Object,
  permisos: {
    type: Object,
    default: () => ({
      ver_todo: false,
      crear: false,
      editar: false
    })
  },
  error: {
    type: String,
    default: ''
  }
})

const confirmDelete = (id) => {
  if (confirm('¿Estás seguro de eliminar este registro?')) {
    window.axios.delete(route('historial-clinico.destroy', id))
      .then(() => {
        // Recargar la página después de eliminar
        window.location.reload()
      })
      .catch(error => {
        console.error('Error al eliminar el registro:', error)
        alert('Ocurrió un error al intentar eliminar el registro.')
      })
  }
}

// Función para formatear la fecha
const formatDate = (dateString) => {
  if (!dateString) return ''
  const options = { year: 'numeric', month: 'short', day: '2-digit' }
  return new Date(dateString).toLocaleDateString('es-ES', options)
}
</script>

<template>
  <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-5xl mx-auto mt-10 px-4">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">
          {{ permisos.ver_todo ? 'Historial Clínico' : 'Mi Historial Clínico' }}
        </h1>
        
        <Link 
          v-if="permisos.crear"
          :href="route('historial-clinico.create')" 
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow-sm transition-colors"
        >
          Nuevo Historial
        </Link>
      </div>

      <div v-if="error" class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">
              {{ error }}
            </p>
          </div>
        </div>
      </div>

      <div v-else-if="historiales.data.length === 0" class="bg-white p-6 rounded-lg shadow text-center">
        <p class="text-gray-600">
          {{ permisos.ver_todo ? 'No hay historiales registrados.' : 'No tienes historiales médicos registrados.' }}
        </p>
      </div>

      <div v-else class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th v-if="permisos.ver_todo" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Paciente
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fecha
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Médico
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Servicio
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Diagnóstico
                </th>
                <th v-if="permisos.ver_todo || permisos.editar" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="h in historiales.data" :key="h.id" class="hover:bg-gray-50">
                <td v-if="permisos.ver_todo" class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ h.cita?.paciente?.usuario?.nombres }} {{ h.cita?.paciente?.usuario?.apellido_paterno }}
                  </div>
                  <div class="text-sm text-gray-500">
                    CI: {{ h.cita?.paciente?.ci || 'N/A' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ formatDate(h.fecha_registro) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    Dr. {{ h.cita?.medico?.usuario?.nombres }} {{ h.cita?.medico?.usuario?.apellido_paterno }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ h.cita?.medico?.especialidad || 'General' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    {{ h.cita?.servicio?.nombre || 'Consulta' }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 font-medium">
                    {{ h.diagnostico || 'Sin diagnóstico registrado' }}
                  </div>
                  <div v-if="h.tratamiento" class="text-sm text-gray-500 mt-1">
                    <span class="font-medium">Tratamiento:</span> {{ h.tratamiento }}
                  </div>
                </td>
                <td v-if="permisos.ver_todo || permisos.editar" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <Link 
                      :href="route('historial-clinico.edit', h.id)" 
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                      v-if="permisos.editar"
                    >
                      Editar
                    </Link>
                    <button 
                      @click="confirmDelete(h.id)"
                      class="text-red-600 hover:text-red-900"
                      v-if="permisos.editar"
                    >
                      Eliminar
                    </button>
                    <Link 
                      v-else
                      :href="route('historial-clinico.show', h.id)" 
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Ver
                    </Link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    <!-- Paginación -->
    <div v-if="historiales.link && Array.isArray(historiales.links) && historiales.links.length > 0" class="flex justify-center gap-2">
      <Link
        v-for="link in historiales.link"
        :key="link.label"
        v-if="link.url !== null && typeof link.url !== 'undefined'"
        :href="link.url"
        :class="[
          'px-3 py-1 rounded',
          link.active ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-green-100'
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
