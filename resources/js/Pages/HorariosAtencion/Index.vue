<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    horarios: Object,
})

const page = usePage()
const success = page.props.flash?.success || null

// Obtener el usuario de la respuesta del controlador
const user = page.props.auth?.user || {}

// Función para verificar si el usuario puede editar un horario
function puedeEditar(horario) {
    // Admin puede editar todo
    if (user.tipo_usuario === 'A') return true
    
    // Médico solo puede editar sus propios horarios
    if (user.tipo_usuario === 'M') {
        // Verificar si el usuario tiene un perfil de médico
        if (!user.medico) return false
        
        // Verificar que el horario pertenece al médico
        return horario.medico_id === user.medico.id
    }
    
    return false
}

// Función para confirmar eliminación
const confirmarEliminar = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
        window.axios.delete(route('horarios-atencion.destroy', id), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            // Si la respuesta es exitosa, recargar la página
            if (response.data && response.data.success) {
                window.location.reload();
            } else {
                throw new Error('Respuesta inesperada del servidor');
            }
        })
        .catch(error => {
            console.error('Error al eliminar el horario:', error);
            if (error.response && error.response.data && error.response.data.message) {
                alert(error.response.data.message);
            } else {
                alert('No se pudo eliminar el horario. Por favor, inténtalo de nuevo.');
            }
        });
    }
}
</script>

<template>
    <AuthenticatedLayout>
    <BackToDashboard />
    <div class="max-w-3xl mx-auto mt-10">
        <div v-if="success" class="mb-4 p-2 bg-green-100 text-green-700 rounded border border-green-300">
            {{ success }}
        </div>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Horarios de Atención</h1>
            <!-- Solo pueden crear médicos y admin -->
            <Link v-if="user.tipo_usuario === 'A' || (user.tipo_usuario === 'M' && user.medico)" :href="route('horarios-atencion.create')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Nuevo Horario
            </Link>
        </div>

        <table class="w-full bg-white dark:bg-slate-800 rounded shadow">
            <thead>
                <tr class="bg-gray-100 dark:bg-slate-700 text-left">
                    <th v-if="user.tipo_usuario === 'A'" class="py-2 px-3">Médico</th>
                    <th class="py-2 px-3">Día</th>
                    <th class="py-2 px-3">Hora inicio</th>
                    <th class="py-2 px-3">Hora fin</th>
                    <th class="py-2 px-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="horario in horarios.data" :key="horario.id" class="border-b hover:bg-gray-50">
                    <td v-if="user.tipo_usuario === 'A'" class="py-2 px-3">
                        <!-- Puedes mostrar el nombre del médico con relación -->
                        {{ horario.medico?.usuario?.nombres }} {{ horario.medico?.usuario?.apellido_paterno }}
                    </td>
                    <td class="py-2 px-3">{{ horario.dia_semana }}</td>
                    <td class="py-2 px-3">{{ horario.hora_inicio }}</td>
                    <td class="py-2 px-3">{{ horario.hora_fin }}</td>
                    <td class="py-2 px-3 text-center">
                        <div class="flex justify-center space-x-4">
                            <Link v-if="puedeEditar(horario)" 
                                  :href="route('horarios-atencion.edit', horario.id)" 
                                  class="btn btn-edit">
                                Editar
                            </Link>
                            <button v-if="puedeEditar(horario)" 
                                    @click="confirmarEliminar(horario.id)"
                                    class="btn btn-danger">
                                Eliminar
                            </button>
                            <span v-if="!puedeEditar(horario)" class="text-gray-400">No disponible</span>
                        </div>
                    </td>
                </tr>
                <tr v-if="!horarios.data.length">
                    <td :colspan="user.tipo_usuario === 'A' ? 5 : 4" class="p-4 text-center text-gray-500">No hay horarios registrados.</td>
                </tr>
            </tbody>
        </table>

        <!-- Paginación -->
        <div v-if="horarios.link && horarios.link.length > 0" class="flex justify-center gap-2 mt-4">
            <Link
                v-for="link in horarios.link"
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

