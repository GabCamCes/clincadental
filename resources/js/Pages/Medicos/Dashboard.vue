<script setup>
import DashboardCard from '@/Components/DashboardCard.vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
const logoutForm = useForm({})

function logout() {
  logoutForm.post(route('logout'))
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto mt-10 p-8 rounded shadow bg-white dark:bg-[#232323] text-black dark:text-white transition">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-primary dark:text-primary">Panel de Médico</h1>
      </div>
      
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <DashboardCard to="/citas" label="Gestionar Citas" />
        <DashboardCard to="/historial-clinico" label="Historial Arte Dental" />
        <DashboardCard :to="route('medico.horarios.index')" label="Mis Horarios" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
