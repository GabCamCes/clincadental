const themes = {
  adultos: {
    name: 'adultos-dia',
    label: 'Adultos (Día)',
    colors: {
      primary: '#2563eb',
      secondary: '#4f46e5',
      background: '#ffffff',
      surface: '#f9fafb',
      text: '#1f2937',
      accent: '#3b82f6',
      error: '#ef4444',
      success: '#10b981',
      warning: '#f59e0b',
    },
    fontSize: '16px',
  },
  ninos: {
    name: 'ninos-dia',
    label: 'Niños (Día)',
    colors: {
      primary: '#ec4899',
      secondary: '#8b5cf6',
      background: '#fef2f2',
      surface: '#fff7ed',
      text: '#1f2937',
      accent: '#f97316',
      error: '#ef4444',
      success: '#10b981',
    },
    fontSize: '18px',
  },
  jovenes: {
    name: 'jovenes-dia',
    label: 'Jóvenes (Día)',
    colors: {
      primary: '#06b6d4',
      secondary: '#3b82f6',
      background: '#f8fafc',
      surface: '#ffffff',
      text: '#1e293b',
      accent: '#8b5cf6',
      error: '#ef4444',
      success: '#10b981',
    },
    fontSize: '16px',
  },
  adultosNoche: {
    name: 'adultos-noche',
    label: 'Adultos (Noche)',
    colors: {
      primary: '#3b82f6',
      secondary: '#2563eb',
      background: '#1f2937',
      surface: '#374151',
      text: '#f9fafb',
      accent: '#60a5fa',
      error: '#f87171',
      success: '#34d399',
    },
    fontSize: '16px',
  },
  ninosNoche: {
    name: 'ninos-noche',
    label: 'Niños (Noche)',
    colors: {
      primary: '#f472b6',
      secondary: '#a78bfa',
      background: '#1f2937',
      surface: '#374151',
      text: '#f9fafb',
      accent: '#fb923c',
      error: '#fca5a5',
      success: '#6ee7b7',
    },
    fontSize: '18px',
  },
  jovenesNoche: {
    name: 'jovenes-noche',
    label: 'Jóvenes (Noche)',
    colors: {
      primary: '#22d3ee',
      secondary: '#60a5fa',
      background: '#1e293b',
      surface: '#334155',
      text: '#f8fafc',
      accent: '#a78bfa',
      error: '#fca5a5',
      success: '#6ee7b7',
    },
    fontSize: '16px',
  },
};

export function getTheme(themeName) {
  const baseTheme = themeName.replace('Noche', '');
  return themes[themeName] || themes[baseTheme] || themes.adultos;
}

export function applyTheme(themeName) {
  try {
    if (!themeName) {
      console.warn('No se proporcionó un nombre de tema, usando tema por defecto');
      themeName = 'adultos';
    }
    
    const theme = getTheme(themeName);
    const root = document.documentElement;
    
    if (!theme) {
      console.error(`Tema '${themeName}' no encontrado`);
      return;
    }
    
    // Aplicar colores con valores por defecto seguros
    const defaultColors = {
      primary: '#2563eb',
      secondary: '#4f46e5',
      background: '#ffffff',
      surface: '#f9fafb',
      text: '#1f2937',
      accent: '#3b82f6',
      error: '#ef4444',
      success: '#10b981',
      warning: '#f59e0b'
    };
    
    const colors = { ...defaultColors, ...(theme.colors || {}) };
    
    Object.entries(colors).forEach(([key, value]) => {
      const cssVar = `--color-${key}`;
      if (value) {
        root.style.setProperty(cssVar, value);
      }
    });
    
    // Aplicar tamaño de fuente con valor por defecto
    const baseFontSize = theme.fontSize || '16px';
    root.style.setProperty('--font-size-base', baseFontSize);
    
    // Guardar preferencia
    try {
      localStorage.setItem('theme', themeName);
      document.documentElement.setAttribute('data-theme', theme.name || 'adultos-dia');
    } catch (e) {
      console.warn('No se pudo guardar la preferencia del tema:', e);
    }
  } catch (error) {
    console.error('Error al aplicar el tema:', error);
    // Aplicar valores por defecto en caso de error
    const root = document.documentElement;
    root.style.setProperty('--color-primary', '#2563eb');
    root.style.setProperty('--color-background', '#ffffff');
    root.style.setProperty('--font-size-base', '16px');
  }
}

// Cargar tema guardado o usar el predeterminado
export function loadTheme() {
  try {
    let savedTheme = 'adultos';
    
    // Intentar cargar el tema guardado
    try {
      const storedTheme = localStorage.getItem('theme');
      if (storedTheme) {
        savedTheme = storedTheme;
      }
    } catch (e) {
      console.warn('No se pudo cargar el tema guardado:', e);
    }
    
    // Aplicar el tema
    applyTheme(savedTheme);
    return savedTheme;
  } catch (error) {
    console.error('Error al cargar el tema:', error);
    applyTheme('adultos');
    return 'adultos';
  }
}

// Alternar entre día y noche
export function toggleDarkMode(themeBase) {
  const isDark = document.documentElement.getAttribute('data-theme')?.includes('noche');
  const newTheme = isDark ? themeBase : `${themeBase}Noche`;
  applyTheme(newTheme);
  return newTheme;
}

// Cambiar tamaño de fuente
export function changeFontSize(direction) {
  const root = document.documentElement;
  const currentSize = parseFloat(getComputedStyle(root).getPropertyValue('--font-size-base') || '16');
  const newSize = direction === 'increase' 
    ? Math.min(currentSize + 2, 24) 
    : Math.max(currentSize - 2, 12);
  root.style.setProperty('--font-size-base', `${newSize}px`);
  localStorage.setItem('fontSize', newSize);
  return newSize;
}

// Cargar tamaño de fuente guardado
export function loadFontSize() {
  try {
    const savedSize = localStorage.getItem('fontSize');
    const defaultSize = 16;
    
    if (savedSize) {
      const size = parseInt(savedSize, 10);
      if (!isNaN(size) && size >= 12 && size <= 24) {
        document.documentElement.style.setProperty('--font-size-base', `${size}px`);
        return size;
      }
    }
    
    // Aplicar tamaño por defecto si hay algún problema
    document.documentElement.style.setProperty('--font-size-base', `${defaultSize}px`);
    return defaultSize;
  } catch (error) {
    console.error('Error al cargar el tamaño de fuente:', error);
    const defaultSize = 16;
    document.documentElement.style.setProperty('--font-size-base', `${defaultSize}px`);
    return defaultSize;
  }
}
