import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import tailwind from '@tailwindcss/vite'

export default defineConfig({
    plugins: [laravel({ input: ['resources/css/app.css','resources/js/app.js'], refresh: true }), vue(), tailwind()],
    server: { host: true, port: 5173, strictPort: true, hmr: { host: 'localhost', port: 5173 } },
})
