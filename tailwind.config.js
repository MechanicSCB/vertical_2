const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ui: {
                    body: 'var(--color-ui-body)',
                    section: 'var(--color-ui-section)',
                    accent: 'var(--color-ui-accent)',
                    secondary: 'var(--color-ui-secondary)',
                    light: 'var(--color-ui-light)',
                    accent_light: 'var(--color-ui-accent-light)',
                    text: {
                        primary: 'var(--color-ui-text-primary)',
                        accent: 'var(--color-ui-text-accent)',
                        accent_inverse: 'var(--color-ui-text-accent-inverse)',
                        secondary: 'var(--color-ui-text-secondary)',
                        light: 'var(--color-ui-text-light)',
                    },
                    link: {
                        text: 'var(--color-ui-link-text)',
                        hover: 'var(--color-ui-link-text-hover)',
                        active: 'var(--color-ui-link-text-active)',
                        disabled: 'var(--color-ui-link-text-disabled)',
                    },
                    border: {
                        primary: 'var(--color-ui-border-primary)',
                        hover: 'var(--color-ui-border-hover)',
                        active: 'var(--color-ui-border-active)',
                    },
                },
                gray: {
                    '50': '#fafafa',
                },
            },
            fontSize: {
                'xxs': '.6rem',
            },
            screens: {
                'xs': '480px',
            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
