import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/main.css',
                'resources/css/header.css',
                'resources/css/footer.css',
                'resources/css/side-navigation.css',
                'resources/js/app.js',
                'resources/js/main.js',
                'resources/js/dropdown.js'
            ],
            refresh: true,
        }),
    ],
});
