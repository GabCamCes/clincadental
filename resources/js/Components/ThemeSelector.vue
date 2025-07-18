<template>
  <div class="relative theme-selector">
    <button 
      @click.stop="isOpen = !isOpen"
      class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-surface hover:bg-opacity-80 transition-colors"
      :aria-expanded="isOpen"
      aria-haspopup="true"
      :aria-label="`Tema actual: ${currentThemeLabel}. Haz clic para cambiar el tema`"
    >
      <span class="text-sm">{{ currentThemeLabel }}</span>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>

    <div 
      v-show="isOpen" 
      class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-surface border border-gray-200 dark:border-gray-700 z-50"
      role="menu"
      aria-orientation="vertical"
      aria-labelledby="theme-menu"
    >
      <div class="p-2 space-y-1">
        <h3 class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wider" id="theme-menu">Temas</h3>
        <button 
          v-for="(theme, key) in themeOptions" 
          :key="key"
          @click="setTheme(key)"
          class="w-full text-left px-3 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
          :class="{ 'bg-gray-100 dark:bg-gray-700': currentTheme === key }"
          role="menuitem"
          :aria-label="`Cambiar a tema ${theme.label}`"
        >
          {{ theme.label }}
          <span v-if="currentTheme === key || currentTheme === `${key}Noche`" class="ml-auto text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </span>
        </button>
        
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
        
        <div class="px-3 py-2">
          <div class="flex justify-between items-center mb-1">
            <span class="text-sm">Tamaño de texto</span>
            <div class="flex space-x-2">
              <button 
                @click="handleFontSizeChange('decrease')" 
                class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                aria-label="Disminuir tamaño de texto"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
              </button>
              <button 
                @click="handleFontSizeChange('increase')" 
                class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                aria-label="Aumentar tamaño de texto"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </button>
            </div>
          </div>
          <div class="text-xs text-gray-500 dark:text-gray-400">Ajusta el tamaño del texto para mejor legibilidad</div>
          
          <div class="mt-3 flex items-center justify-between">
            <span class="text-sm">Modo oscuro</span>
            <button 
              @click="toggleDark"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
              :class="isDarkMode ? 'bg-primary' : 'bg-gray-200 dark:bg-gray-700'"
              role="switch"
              :aria-checked="isDarkMode"
              :aria-label="isDarkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
            >
              <span class="sr-only">Modo oscuro</span>
              <span 
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                :class="isDarkMode ? 'translate-x-6' : 'translate-x-1'"
              ></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { getTheme, applyTheme, loadTheme, toggleDarkMode, changeFontSize, loadFontSize } from '../Theme/themeManager';

// Inicializar variables CSS personalizadas
function initThemeVariables() {
  const root = document.documentElement;
  const defaultTheme = {
    '--color-primary': '#2563eb',
    '--color-secondary': '#4f46e5',
    '--color-background': '#ffffff',
    '--color-surface': '#f9fafb',
    '--color-text': '#1f2937',
    '--color-accent': '#3b82f6',
    '--color-error': '#ef4444',
    '--color-success': '#10b981',
    '--color-warning': '#f59e0b',
  };

  // Asegurar que todas las variables CSS estén definidas
  Object.entries(defaultTheme).forEach(([key, value]) => {
    if (!getComputedStyle(root).getPropertyValue(key)) {
      root.style.setProperty(key, value);
    }
  });
}

const isOpen = ref(false);
const currentTheme = ref('adultos');
const isDarkMode = ref(false);

const themeOptions = {
  adultos: { label: 'Adultos' },
  ninos: { label: 'Niños' },
  jovenes: { label: 'Jóvenes' },
};

const currentThemeLabel = computed(() => {
  const baseTheme = currentTheme.value.replace('Noche', '');
  const theme = themeOptions[baseTheme] || { label: 'Tema' };
  return isDarkMode.value ? `${theme.label} (Noche)` : theme.label;
});

const setTheme = (theme) => {
  currentTheme.value = theme;
  applyTheme(isDarkMode.value ? `${theme}Noche` : theme);
  isOpen.value = false;
};

const toggleDark = () => {
  // Obtener el tema base sin el sufijo 'Noche'
  const themeBase = currentTheme.value.endsWith('Noche')
    ? currentTheme.value.replace('Noche', '')
    : currentTheme.value;
  
  // Alternar el modo oscuro usando themeManager
  toggleDarkMode(themeBase);
  
  // Actualizar el estado local
  isDarkMode.value = !isDarkMode.value;
  
  // Forzar actualización del tema
  const newTheme = isDarkMode.value ? `${themeBase}Noche` : themeBase;
  currentTheme.value = newTheme;
  
  // Disparar evento personalizado para notificar a otros componentes
  window.dispatchEvent(new CustomEvent('theme-changed', { 
    detail: { 
      theme: newTheme,
      isDark: isDarkMode.value
    } 
  }));
};

const handleFontSizeChange = (direction) => {
  changeFontSize(direction);
};

const handleClickOutside = (event) => {
  if (!event.target.closest('.theme-selector')) {
    isOpen.value = false;
  }
};

onMounted(() => {
  try {
    // Inicializar variables CSS personalizadas
    initThemeVariables();
    
    // Cargar tema guardado
    const savedTheme = loadTheme();
    const isDark = savedTheme && savedTheme.includes('Noche');
    const baseTheme = savedTheme ? savedTheme.replace('Noche', '') : 'adultos';
    
    if (themeOptions[baseTheme]) {
      currentTheme.value = baseTheme;
      isDarkMode.value = isDark;
      
      // Aplicar tema guardado
      applyTheme(isDark ? `${baseTheme}Noche` : baseTheme);
    } else {
      // Tema por defecto si el guardado no existe
      console.warn('Tema guardado no encontrado, usando tema por defecto');
      currentTheme.value = 'adultos';
      isDarkMode.value = false;
      applyTheme('adultos');
    }
    
    // Cargar tamaño de fuente
    const savedFontSize = loadFontSize();
    if (savedFontSize) {
      document.documentElement.style.fontSize = savedFontSize;
    } else {
      // Tamaño de fuente por defecto
      document.documentElement.style.fontSize = '16px';
    }
    
    // Agregar manejador de clic fuera del menú
    document.addEventListener('click', handleClickOutside);
  } catch (error) {
    console.error('Error al inicializar el tema:', error);
    // Asegurar valores por defecto en caso de error
    document.documentElement.style.setProperty('--color-primary', '#2563eb');
    document.documentElement.style.setProperty('--color-background', '#ffffff');
    document.documentElement.style.fontSize = '16px';
  }
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* Transiciones suaves para el menú desplegable */
.absolute {
  transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
  transform-origin: top right;
}

.absolute[style*="display: none"] {
  opacity: 0;
  transform: scale(0.95);
  pointer-events: none;
}

.absolute[style*="display: block"] {
  opacity: 1;
  transform: scale(1);
}
</style>
