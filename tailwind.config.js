import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Mukta', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                nepal: {
                    red: '#DC143C',
                    crimson: '#C41230',
                    blue: '#003893',
                    darkblue: '#002566',
                    gold: '#D4AF37',
                }
            }
        },
    },

    plugins: [forms, typography],
};
