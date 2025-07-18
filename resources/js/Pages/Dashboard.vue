<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto mt-10 p-8 bg-white rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Bienvenido al Panel de Administración</h1>
      <p class="mb-6">
        Desde este panel puedes gestionar todas las entidades de Arte Dental:
      </p>
      <ul class="list-disc ml-6 space-y-2">
        <li>Usuarios</li>
        <li>Especialidades</li>
        <li>Salas</li>
        <li>Productos</li>
        <li>Servicios</li>
        <li>Pacientes</li>
        <li>Médicos</li>
        <li>Promociones</li>
        <li>Citas</li>
        <li>Horarios de Atención</li>
        <li>Historial Arte Dental</li>
        <li>Ventas</li>
        <li>Pagos</li>
        <li>Historial de Pagos</li>
      </ul>
      <p class="mt-8 text-center text-gray-500 text-sm">
        Usa el menú superior para navegar entre las diferentes secciones.
      </p>
    </div>
      <footer class="mt-10 text-center text-gray-400 text-sm">
      2025 Grupo 25. Todos los derechos reservados
    </footer>
  </AuthenticatedLayout>
</template>

