<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Doughnut } from 'vue-chartjs'
import { Chart, ArcElement, Tooltip, Legend } from 'chart.js'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BackToDashboard from '@/Components/BackToDashboard.vue'
Chart.register(ArcElement, Tooltip, Legend)

const page = usePage()
const productosPopulares = computed(() => page.props.productosPopulares || [])
const serviciosPopulares = computed(() => page.props.serviciosPopulares || [])

// Paletas de colores bonitas para las donas
const colores = [
  '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'
]
</script>

<template>
  <AuthenticatedLayout>
    <BackToDashboard />
  <div class="max-w-4xl mx-auto my-8">
    <h1 class="text-3xl font-bold mb-8">Estadísticas del Negocio</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

      <!-- Productos más vendidos (Dona) -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex flex-col items-center">
        <h2 class="text-xl font-semibold mb-4 text-center">Productos más comprados</h2>
        <Doughnut
          v-if="productosPopulares.length"
          :data="{
            labels: productosPopulares.map(p => p.nombre),
            datasets: [{
              data: productosPopulares.map(p => p.total_vendidos),
              backgroundColor: colores
            }]
          }"
          :options="{
            responsive: true,
            plugins: {
              legend: { position: 'bottom' }
            }
          }"
          style="max-width: 350px; margin: auto"
        />
        <div v-else class="text-gray-500 mt-4">No hay datos suficientes para mostrar.</div>
      </div>

      <!-- Servicios más pedidos (Dona) -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex flex-col items-center">
        <h2 class="text-xl font-semibold mb-4 text-center">Servicios más pedidos</h2>
        <Doughnut
          v-if="serviciosPopulares.length"
          :data="{
            labels: serviciosPopulares.map(s => s.nombre),
            datasets: [{
              data: serviciosPopulares.map(s => s.total_pedidos),
              backgroundColor: colores
            }]
          }"
          :options="{
            responsive: true,
            plugins: {
              legend: { position: 'bottom' }
            }
          }"
          style="max-width: 350px; margin: auto"
        />
        <div v-else class="text-gray-500 mt-4">No hay datos suficientes para mostrar.</div>
      </div>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
