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
import { loadTheme, loadFontSize } from './Theme/themeManager';

// Aplicar el tema guardado al cargar la aplicación
if (typeof window !== 'undefined') {
  // Forzar la recarga del tema después de que se cargue todo
  const applyTheme = () => {
    try {
      const theme = localStorage.getItem('theme') || 'adultos';
      const isDark = localStorage.getItem('darkMode') === 'true';
      const themeToApply = isDark ? `${theme}Noche` : theme;
      
      // Aplicar el tema al documento
      document.documentElement.setAttribute('data-theme', themeToApply);
      
      // Cargar el tema y tamaño de fuente
      loadTheme();
      loadFontSize();
      
      // Forzar repintado para asegurar que los estilos se apliquen
      document.body.style.display = 'none';
      // eslint-disable-next-line no-unused-expressions
      document.body.offsetHeight; // Trigger reflow
      document.body.style.display = '';
    } catch (error) {
      console.error('Error al aplicar el tema:', error);
    }
  };
  
  // Aplicar el tema después de que se cargue el DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyTheme);
  } else {
    applyTheme();
  }
  
  // Asegurar que el tema se aplique incluso si hay un retraso en la carga
  setTimeout(applyTheme, 300);
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
