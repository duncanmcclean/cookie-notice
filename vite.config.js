import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            hotFile: "vite.hot",
            publicDirectory: "dist",
            input: ['resources/css/cookie-notice.css'],
        }),
    ],
});
