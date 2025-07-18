
<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-white dark:bg-[#18181c]">
    <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl px-8 py-10 w-full max-w-lg flex flex-col items-center">
      <img src="/images/logo.jpg" alt="Logo" width="100" height="100">
      <h1 class="text-3xl font-bold text-blue-700 dark:text-blue-300 mb-3">Bienvenido a la Clínica Dental</h1>
      <h1 class="text-3xl font-bold text-blue-700 dark:text-blue-300 mb-3">"ARTE DENTAL"</h1>
      <p class="text-gray-600 dark:text-gray-300 mb-6 text-center">
        Sistema de gestión de citas, pacientes y servicios dentales.<br />
        Ingrese con su cuenta para comenzar.
      </p>
      <Link
        :href="route('login')"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded transition"
      >
        Iniciar sesión
      </Link>
    </div>
    <footer class="mt-10 text-gray-400 dark:text-gray-500 text-sm">
      © {{ new Date().getFullYear() }} Clínica Dental | Desarrollado por Grupo 25
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

