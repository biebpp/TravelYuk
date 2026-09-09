import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'ocean-0': '#EEFFFC',
                'ocean-1': '#CAF0F8',
                'ocean-2': '#90E0EF',
                'ocean-3': '#00B4D8',
                'ocean-4': '#0077B6',
                'ocean-5': '#03045E',
                'sea-1': '#8ECAE6',
                'sea-2': '#219EBC',
                'sea-3': '#023047',
                'summer-1': '#FB8500',
                'summer-2': '#FFB703',
            },
            boxShadow: {
                't-sm': '0 -1px 2px 0 rgba(0, 0, 0, 0.05)',
                't-md': '0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -2px rgba(0, 0, 0, 0.1)',
                't-lg': '0 -10px 15px -3px rgba(0, 0, 0, 0.1), 0 -4px 6px -4px rgba(0, 0, 0, 0.1)',
                't-xl': '0 -20px 25px -5px rgba(0, 0, 0, 0.1), 0 -8px 10px -6px rgba(0, 0, 0, 0.1)',
                't-2xl': '0 -25px 50px -12px rgba(0, 0, 0, 0.25)',
            }
        },
    },

    plugins: [forms],
};
