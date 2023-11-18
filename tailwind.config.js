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
        "./node_modules/tw-elements/dist/js/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // orange color shades
                orange: {
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
                // sky color shades
                primary: {
                    50: "#f6faff",
                    100: "#edf5ff",
                    200: "#d3e4ff",
                    300: "#b9d2ff",
                    400: "#7faeff",
                    500: "#468bff",
                    600: "#3f7de6",
                    700: "#3568bf",
                    800: "#2b5399",
                    900: "#23447d",
                },
            },
        },
    },
    darkMode: "class",
    plugins: [forms, typography],
};
