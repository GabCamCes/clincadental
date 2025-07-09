<script setup>
import { ref, onMounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import ThemeSelector from '@/Components/ThemeSelector.vue'

const page = usePage()
const menu = page.props.menu || []
const contadorVisitas = page.props.contador_visitas || 0
const busqueda = ref('')

// Función para buscar
function buscar() {
  if (busqueda.value.trim()) {
    router.get('/buscar', { query: busqueda.value })
  }
}

// Cerrar sesión
function logout() {
  router.post('/logout')
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-background text-text transition-theme">
    <!-- Barra superior -->
    <nav class="flex items-center px-6 py-4 border-b shadow bg-surface text-text transition-theme">
      <!-- Logo a la izquierda -->
      <Link 
        :href="$page.props.auth.user?.tipo_usuario === 'A' ? route('usuario.dashboard') : 
              $page.props.auth.user?.tipo_usuario === 'M' ? route('medico.dashboard') : 
              $page.props.auth.user?.tipo_usuario === 'P' ? route('paciente.dashboard') : route('welcome')" 
        class="mr-8 text-2xl font-bold hover:opacity-80 whitespace-nowrap transition-opacity" 
        :style="{ color: 'var(--color-primary)' }"
      >
        Clínica Dental
      </Link>
      
      <!-- Menú dinámico (centro/izquierda) -->
      <div class="flex gap-4 flex-1">
        <Link
          v-for="item in menu"
          :key="item.id"
          :href="item.ruta"
          class="font-semibold hover:opacity-80 text-primary flex items-center gap-1 transition-opacity"
        >
          <span v-if="item.icono">{{ item.icono }}</span>
          {{ item.nombre }}
        </Link>
      </div>
      
      <!-- Búsqueda y controles (derecha) -->
      <div class="flex items-center gap-4 ml-auto">
        <!-- Selector de temas -->
        <ThemeSelector class="theme-selector" />
        
        <!-- Barra de búsqueda -->
        <form @submit.prevent="buscar" class="flex items-center">
          <input
            v-model="busqueda"
            type="text"
            placeholder="Buscar..."
            class="rounded-l border border-gray-300 dark:border-gray-600 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-surface text-text"
            aria-label="Buscar en el sitio"
          />
          <button
            type="submit"
            class="bg-primary hover:bg-opacity-90 text-white px-4 py-1.5 rounded-r transition-colors"
            aria-label="Buscar"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </form>
        
        <!-- Botón de cerrar sesión -->
        <button
          @click="logout"
          class="ml-2 px-4 py-1.5 rounded font-bold bg-error hover:bg-opacity-90 text-white transition-colors flex items-center gap-1.5"
          aria-label="Cerrar sesión"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span class="hidden sm:inline">Cerrar sesión</span>
        </button>
      </div>
    </nav>

    <!-- Contenido principal -->
    <main class="flex-1 px-4 sm:px-6 py-6">
      <div class="max-w-7xl mx-auto">
        <slot />
      </div>
    </main>

    <!-- Pie de página: contador de visitas -->
    <footer class="mt-auto py-3 bg-surface border-t border-gray-200 dark:border-gray-700 transition-theme">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-text/80">
          <div class="mb-2 md:mb-0">
            &copy; {{ new Date().getFullYear() }} Clínica Dental. Todos los derechos reservados.
          </div>
          <div class="flex items-center
          ">
            <span class="inline-flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Visitas: <span class="font-medium ml-1">{{ $page.props.contador_visitas }}</span>
            </span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>







