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
      <div class="card bg-primary/10 p-5 text-center hover:shadow-lg border-primary/30 transition-all duration-300">
        <div class="text-3xl font-bold text-primary">{{ props.estadisticas?.pacientes ?? 0 }}</div>
        <div class="text-text/80 text-sm font-medium mt-1">Pacientes</div>
      </div>
      <div class="card bg-secondary/10 p-5 text-center hover:shadow-lg border-secondary/30 transition-all duration-300">
        <div class="text-3xl font-bold text-secondary">{{ props.estadisticas?.medicos ?? 0 }}</div>
        <div class="text-text/80 text-sm font-medium mt-1">Médicos</div>
      </div>
      <div class="card bg-success/10 p-5 text-center hover:shadow-lg border-success/30 transition-all duration-300">
        <div class="text-3xl font-bold text-success">{{ props.estadisticas?.citas ?? 0 }}</div>
        <div class="text-text/80 text-sm font-medium mt-1">Citas</div>
      </div>
      <div class="card bg-accent/10 p-5 text-center hover:shadow-lg border-accent/30 transition-all duration-300">
        <div class="text-3xl font-bold text-accent">{{ props.estadisticas?.productos ?? 0 }}</div>
        <div class="text-text/80 text-sm font-medium mt-1">Productos</div>
      </div>
      <div class="card bg-warning/10 p-5 text-center hover:shadow-lg border-warning/30 transition-all duration-300">
        <div class="text-3xl font-bold text-warning">{{ props.estadisticas?.servicios ?? 0 }}</div>
        <div class="text-text/80 text-sm font-medium mt-1">Servicios</div>
      </div>
    </div>
  <div class="max-w-5xl mx-auto mt-10 p-8 rounded shadow card bg-surface text-text transition">
        
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-primary">Panel de Administración</h1>
      
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


