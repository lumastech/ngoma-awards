import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // orange color shades
                primary: {
                    50: "#fff7f0",
                    100: "#ffeedb",
                    200: "#ffdcb2",
                    300: "#ffc988",
                    400: "#ffb65f",
                    500: "#ffa435",
                    600: "#e68c2f",
                    700: "#bf7428",
                    800: "#995b21",
                    900: "#7d4c1d",
                },
            },
        },
    },

    plugins: [forms, typography],
};
