import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from '@tailwindcss/vite';
import statamic from '@statamic/cms/vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            hotFile: "vite.hot",
            publicDirectory: "dist",
            input: [
                'resources/css/cookie-notice.css',
                'resources/js/cookie-notice.js',
                'resources/js/cp.js',
            ],
        }),
        statamic(),
        tailwindcss(),
    ],
});
