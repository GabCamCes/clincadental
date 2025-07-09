<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user

function dashboardRoute() {
  if (!user) return route('dashboard')
  if (user.tipo_usuario === 'A') return route('usuario.dashboard')
  if (user.tipo_usuario === 'M') return route('medico.dashboard')
  if (user.tipo_usuario === 'P') return route('paciente.dashboard')
  return route('dashboard')
}
</script>

<template>
  <div class="mb-4">
    <Link
      :href="dashboardRoute()"
      class="inline-flex items-center px-4 py-2 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold shadow hover:bg-gray-300 hover:dark:bg-gray-600 transition"
    >
      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      Volver al Dashboard
    </Link>
  </div>
</template>

