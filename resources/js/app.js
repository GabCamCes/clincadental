import '../css/theme.css';
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Toast from 'vue3-toastify';

// Cargar el tema al inicio
import { loadTheme, loadFontSize, getCurrentTheme } from './Theme/themeManager';

// Función para aplicar el tema de manera segura
const applyThemeSafely = () => {
  try {
    // Cargar preferencias guardadas
    const savedTheme = localStorage.getItem('theme') || 'adultos';
    const isDark = localStorage.getItem('darkMode') === 'true';
    
    // Aplicar el tema usando themeManager
    loadTheme();
    
    // Sincronizar la clase dark de Tailwind
    if (isDark) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
    
    // Cargar el tamaño de fuente
    loadFontSize();
    
    // Forzar actualización de estilos
    const updateStyles = () => {
      document.body.style.display = 'none';
      // eslint-disable-next-line no-unused-expressions
      document.body.offsetHeight; // Trigger reflow
      document.body.style.display = '';
    };
    
    // Asegurar que los estilos se actualicen
    requestAnimationFrame(() => {
      updateStyles();
      // Verificar nuevamente después de la animación
      requestAnimationFrame(updateStyles);
    });
    
    // Registrar el tema actual para depuración
    console.log(`Tema aplicado: ${getCurrentTheme()}`, { isDark });
    
  } catch (error) {
    console.error('Error al aplicar el tema:', error);
  }
};

// Aplicar el tema cuando el DOM esté listo
if (typeof window !== 'undefined') {
  // Aplicar inmediatamente si el DOM ya está listo
  if (document.readyState === 'complete' || document.readyState === 'interactive') {
    applyThemeSafely();
  } else {
    // O esperar a que el DOM esté listo
    document.addEventListener('DOMContentLoaded', applyThemeSafely);
  }
  
  // Aplicar después de un breve retraso para manejar casos extremos
  setTimeout(applyThemeSafely, 300);
  
  // Escuchar cambios en el almacenamiento local para actualizar el tema en tiempo real
  window.addEventListener('storage', (event) => {
    if (['theme', 'darkMode'].includes(event.key)) {
      applyThemeSafely();
    }
  });
}

// Configuración global de toast
const toastOptions = {
  autoClose: 5000,
  position: 'bottom-right',
  hideProgressBar: false,
  closeOnClick: true,
  pauseOnHover: true,
  draggable: true,
  theme: 'colored',
  transition: 'Vue-Toastification__bounce',
  maxToasts: 5,
  newestOnTop: true
};

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, toastOptions);
            
        // Hacer toast disponible globalmente
        app.config.globalProperties.$toast = toast;
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
