import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/* @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/protonemedia/laravel-splade/lib/**/*.vue",
        "./vendor/protonemedia/laravel-splade/resources/views/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./resources/js/**/*.js",
        // "./app/Forms/*.php",
        // "./app/Tables/*.php",
    ],

    safelist: [
        {
            pattern: /text-(.*?)-\d00/
        },
        {
            pattern: /bg-(.*?)-\d00/
        },
        'text-wrap'
    ],

    theme: {
        extend: {},
    },

    plugins: [forms, typography],
};
