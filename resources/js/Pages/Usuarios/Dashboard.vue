<script setup>
import DashboardCard from '@/Components/DashboardCard.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
const logoutForm = useForm({})

const props = defineProps({
  estadisticas: Object
})

function logout() {
  logoutForm.post(route('logout'))
}
</script>

<template>
  <AuthenticatedLayout>
      <div class="mb-6 grid grid-cols-2 md:grid-cols-5 gap-4">
      <div class="bg-blue-50 p-4 rounded-xl shadow text-center">
        <div class="text-2xl font-bold text-blue-700">{{ props.estadisticas?.pacientes ?? 0 }}</div>
        <div class="text-gray-700 text-sm">Pacientes</div>
      </div>
      <div class="bg-green-50 p-4 rounded-xl shadow text-center">
        <div class="text-2xl font-bold text-green-700">{{ props.estadisticas?.medicos ?? 0 }}</div>
        <div class="text-gray-700 text-sm">Médicos</div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-xl shadow text-center">
        <div class="text-2xl font-bold text-yellow-700">{{ props.estadisticas?.citas ?? 0 }}</div>
        <div class="text-gray-700 text-sm">Citas</div>
      </div>
      <div class="bg-purple-50 p-4 rounded-xl shadow text-center">
        <div class="text-2xl font-bold text-purple-700">{{ props.estadisticas?.productos ?? 0 }}</div>
        <div class="text-gray-700 text-sm">Productos</div>
      </div>
      <div class="bg-pink-50 p-4 rounded-xl shadow text-center">
        <div class="text-2xl font-bold text-pink-700">{{ props.estadisticas?.servicios ?? 0 }}</div>
        <div class="text-gray-700 text-sm">Servicios</div>
      </div>
    </div>
  <div class="max-w-5xl mx-auto mt-10 p-8 rounded shadow bg-white dark:bg-[#232323] text-black dark:text-white transition">
        
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold" :style="{ color: 'var(--color-primary)' }">Panel de Administración</h1>
      
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <DashboardCard to="/usuarios" label="Usuarios" />
      <DashboardCard to="/especialidades" label="Especialidades" />
      <DashboardCard to="/productos" label="Productos" />
      <DashboardCard to="/servicios" label="Servicios" />
      <DashboardCard to="/pacientes" label="Pacientes" />
      <DashboardCard to="/medicos" label="Médicos" />
      <DashboardCard to="/citas" label="Citas" />
      <DashboardCard to="/historial-clinico" label="Historial Clínico" />
      <DashboardCard to="/horarios-atencion" label="Horarios de Atención" />
      <DashboardCard to="/salas" label="Salas" />
      <DashboardCard to="/ventas" label="Ventas" />
      <DashboardCard to="/pagos" label="Pagos" />
      <DashboardCard to="/promociones" label="Promociones" />
      <DashboardCard to="/historial-pagos" label="Historial de Pagos" />
      <DashboardCard to="/estadisticas" label="Estadísticas del Sistema" />
    </div>
  </div>
  </AuthenticatedLayout>
</template>


