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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: 'var(--color-primary, #2563eb)',
                secondary: 'var(--color-secondary, #4f46e5)',
                background: 'var(--color-background, #ffffff)',
                surface: 'var(--color-surface, #f9fafb)',
                text: 'var(--color-text, #1f2937)',
                accent: 'var(--color-accent, #3b82f6)',
                error: 'var(--color-error, #ef4444)',
                success: 'var(--color-success, #10b981)',
                warning: 'var(--color-warning, #f59e0b)',
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
