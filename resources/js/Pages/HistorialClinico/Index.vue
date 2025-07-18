<script setup>
function translatePaginationLabel(label) {
  if (!label) return '';
  if (label.includes('Previous') || label === 'pagination.previous' || label.includes('«')) return 'Página anterior';
  if (label.includes('Next') || label === 'pagination.next' || label.includes('»')) return 'Página siguiente';
  return label.replace(/&laquo;|&raquo;/g, '').trim();
}
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
          {{ permisos.ver_todo ? 'Historial Arte Dental' : 'Mi Historial Arte Dental' }}
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

      <div v-else class="w-full bg-white dark:bg-slate-800 rounded shadow mb-6">
        <div class="overflow-x-auto">
          <table class="w-full bg-white dark:bg-slate-800 rounded">
            <thead>
              <tr class="bg-gray-100 dark:bg-slate-700 text-left">
                <th v-if="permisos.ver_todo" class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                <th class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                <th class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                <th class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnóstico</th>
                <th v-if="permisos.ver_todo || permisos.editar" class="py-2 px-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="h in historiales.data" :key="h.id" class="border-b hover:bg-gray-50 dark:hover:bg-slate-700">
                <td v-if="permisos.ver_todo" class="py-2 px-3">
                  <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                    {{ h.cita?.paciente?.usuario?.nombres }} {{ h.cita?.paciente?.usuario?.apellido_paterno }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    CI: {{ h.cita?.paciente?.ci || 'N/A' }}
                  </div>
                </td>
                <td class="py-2 px-3">
                  <div class="text-sm text-gray-900 dark:text-gray-200">
                    {{ formatDate(h.fecha_registro) }}
                  </div>
                </td>
                <td class="py-2 px-3">
                  <div class="text-sm text-gray-900 dark:text-gray-200">
                    Dr. {{ h.cita?.medico?.usuario?.nombres }} {{ h.cita?.medico?.usuario?.apellido_paterno }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ h.cita?.medico?.especialidad || 'General' }}
                  </div>
                </td>
                <td class="py-2 px-3">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ h.cita?.servicio?.nombre || 'Consulta' }}
                  </span>
                </td>
                <td class="py-2 px-3">
                  <div class="text-sm text-gray-900 font-medium dark:text-gray-200">
                    {{ h.diagnostico || 'Sin diagnóstico registrado' }}
                  </div>
                  <div v-if="h.tratamiento" class="text-sm text-gray-500 mt-1 dark:text-gray-400">
                    <span class="font-medium">Tratamiento:</span> {{ h.tratamiento }}
                  </div>
                </td>
                <td v-if="permisos.ver_todo || permisos.editar" class="py-2 px-3 text-right">
                  <div class="flex justify-end space-x-2">
                    <Link 
                      :href="route('historial-clinico.edit', h.id)" 
                      class="btn btn-edit"
                      v-if="permisos.editar"
                    >
                      Editar
                    </Link>
                    <button 
                      @click="confirmDelete(h.id)"
                      class="btn btn-danger"
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
    <div v-if="historiales.links && Array.isArray(historiales.links) && historiales.links.length > 0" class="flex justify-center gap-2 mt-4">
      <template v-for="(link, idx) in historiales.links" :key="idx">
        <Link
          v-if="link.url"
          :href="link.url"
          :class="[
            'px-3 py-1 rounded',
            link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 hover:bg-blue-200 dark:hover:bg-slate-600'
          ]"
          v-html="translatePaginationLabel(link.label)"
        />
        <span
          v-else
          :class="[
            'px-3 py-1 rounded text-gray-400 select-none',
            link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-slate-700 dark:text-gray-200'
          ]"
          v-html="translatePaginationLabel(link.label)"
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
