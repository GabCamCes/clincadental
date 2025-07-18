import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colores base con valores por defecto en formato RGB
                primary: 'rgb(var(--color-primary) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary) / <alpha-value>)',
                accent: 'rgb(var(--color-accent) / <alpha-value>)',
                background: 'rgb(var(--color-background) / <alpha-value>)',
                surface: 'rgb(var(--color-surface) / <alpha-value>)',
                text: 'rgb(var(--color-text) / <alpha-value>)',
                border: 'rgb(var(--color-border) / <alpha-value>)',
                error: 'rgb(var(--color-error) / <alpha-value>)',
                success: 'rgb(var(--color-success) / <alpha-value>)',
                warning: 'rgb(var(--color-warning) / <alpha-value>)',
                
                // Alias para compatibilidad
                bg: 'rgb(var(--color-bg) / <alpha-value>)',
                card: 'rgb(var(--color-card) / <alpha-value>)',
                
                // Colores para modo oscuro
                dark: {
                    primary: 'rgb(var(--color-primary-dark) / <alpha-value>)',
                    secondary: 'rgb(var(--color-secondary-dark) / <alpha-value>)',
                    background: 'rgb(var(--color-background-dark) / <alpha-value>)',
                    surface: 'rgb(var(--color-surface-dark) / <alpha-value>)',
                    text: 'rgb(var(--color-text-dark) / <alpha-value>)',
                    border: 'rgb(var(--color-border-dark) / <alpha-value>)',
                },
            },
            fontSize: {
                base: 'var(--font-size-base, 1rem)',
                sm: 'calc(var(--font-size-base, 1rem) * 0.875)',
                lg: 'calc(var(--font-size-base, 1rem) * 1.125)',
                xl: 'calc(var(--font-size-base, 1rem) * 1.25)',
                '2xl': 'calc(var(--font-size-base, 1rem) * 1.5)',
                '3xl': 'calc(var(--font-size-base, 1rem) * 1.875)',
            },
            transitionProperty: {
                'theme': 'background-color, border-color, color, fill, stroke, opacity, box-shadow, transform',
            },
            transitionDuration: {
                'theme': '300ms',
            },
            transitionTimingFunction: {
                'theme': 'cubic-bezier(0.4, 0, 0.2, 1)',
            },
        },
    },

    plugins: [
        forms,
        function({ addBase, theme }) {
            addBase({
                ':root': {
                    '--color-primary': theme('colors.blue.600'),
                    '--color-secondary': theme('colors.indigo.600'),
                    '--color-background': theme('colors.white'),
                    '--color-surface': theme('colors.gray.50'),
                    '--color-text': theme('colors.gray.800'),
                    '--color-accent': theme('colors.blue.500'),
                    '--color-error': theme('colors.red.500'),
                    '--color-success': theme('colors.green.500'),
                    '--color-warning': theme('colors.yellow.500'),
                    '--font-size-base': '16px',
                },
                '.dark': {
                    '--color-primary': theme('colors.blue.500'),
                    '--color-secondary': theme('colors.indigo.500'),
                    '--color-background': theme('colors.gray.900'),
                    '--color-surface': theme('colors.gray.800'),
                    '--color-text': theme('colors.gray.100'),
                    '--color-accent': theme('colors.blue.400'),
                    '--color-error': theme('colors.red.400'),
                    '--color-success': theme('colors.green.400'),
                    '--color-warning': theme('colors.yellow.400'),
                },
                'body': {
                    'transition': 'background-color 0.3s ease, color 0.3s ease',
                    'background-color': 'var(--color-background)',
                    'color': 'var(--color-text)',
                },
            });
        },
    ],
};
