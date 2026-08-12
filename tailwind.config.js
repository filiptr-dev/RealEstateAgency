import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    // Preflight disabled: this project's UI is a legacy Bootstrap 3 template
    // and Tailwind's base reset (h1-h6 sizes, link colors, form styles, Figtree font)
    // was collapsing headings, discoloring links, and restyling every bare <input>
    // globally. Tailwind is only pulled in as a Vite entrypoint to bundle Leaflet;
    // we do not want its base layer active.
    corePlugins: {
        preflight: false,
    },

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    // forms plugin scoped with strategy:'class' so it only styles opt-in
    // .form-input / .form-select etc., not every bare <input> in Bootstrap markup.
    plugins: [forms({ strategy: 'class' })],
};
