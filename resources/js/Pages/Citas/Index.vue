<script setup>
import { Link } from '@inertiajs/vue3'
import BackToDashboard from '@/Components/BackToDashboard.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const props = defineProps({ citas: Object, tipo_usuario: String })

function nombreCompleto(usuario) {
    return `${usuario.nombres} ${usuario.apellido_paterno} ${usuario.apellido_materno}`
}
</script>

<template>
  <AuthenticatedLayout>
  <BackToDashboard />
  <div class="max-w-6xl mx-auto mt-10">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Citas</h1>
      <!-- Solo mostrar "Nueva Cita" a paciente o admin -->
      <Link
        v-if="props.tipo_usuario === 'A' || props.tipo_usuario === 'P'"
        :href="route('citas.create')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
      >Nueva Cita</Link>
    </div>

    <table class="w-full bg-white rounded shadow mb-6">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="py-2 px-3" v-if="props.tipo_usuario !== 'P'">Paciente</th>
          <th class="py-2 px-3">Médico</th>
          <th class="py-2 px-3">Servicio</th>
          <th class="py-2 px-3">Sala</th>
          <th class="py-2 px-3">Fecha</th>
          <th class="py-2 px-3">Hora</th>
          <th class="py-2 px-3">Estado</th>
          <th class="py-2 px-3 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cita in citas.data" :key="cita.id" class="border-b hover:bg-gray-50">
          <td class="py-2 px-3" v-if="props.tipo_usuario !== 'P'">{{ nombreCompleto(cita.paciente.usuario) }}</td>
          <td class="py-2 px-3">{{ nombreCompleto(cita.medico.usuario) }}</td>
          <td class="py-2 px-3">{{ cita.servicio?.nombre }}</td>
          <td class="py-2 px-3">{{ cita.sala?.nombre }}</td>
          <td class="py-2 px-3">{{ cita.fecha }}</td>
          <td class="py-2 px-3">{{ cita.hora }}</td>
          <td class="py-2 px-3">{{ cita.estado }}</td>
          <td class="py-2 px-3 text-center flex gap-2 justify-center">
            <!-- Solo ADMIN o MÉDICO pueden editar/eliminar, paciente no -->
            <template v-if="props.tipo_usuario === 'A' || props.tipo_usuario === 'M'">
              <Link :href="route('citas.edit', cita.id)" class="text-blue-600 underline">Editar</Link>
              <form :action="route('citas.destroy', cita.id)" method="post" @submit.prevent="$inertia.delete(route('citas.destroy', cita.id))" class="inline">
                <button type="submit" class="text-red-600 underline">Eliminar</button>
              </form>
            </template>
            <!-- Paciente no ve estas opciones -->
          </td>
        </tr>
      </tbody>
    </table>
    <!-- Paginación -->
    <div v-if="citas.link && citas.link.length > 0" class="flex justify-center gap-2">
      <Link
        v-for="link in citas.link"
        :key="link.label"
        v-if="link.url"
        :href="link.url"
        :class="['px-3 py-1 rounded', link.active ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-100']"
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

