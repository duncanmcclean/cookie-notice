import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  build: {
    manifest: false,
    rollupOptions: {
      output: {
        assetFileNames: '[name][extname]',
      }
    }
  },
  plugins: [
    laravel({
      hotFile: "vite.hot",
      publicDirectory: "dist",
      buildDirectory: "css",
      input: ["resources/css/cookie-notice.css"],
    }),
  ],
});
