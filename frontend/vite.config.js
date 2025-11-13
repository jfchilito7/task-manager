import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    tailwindcss()
  ],
  // AGREGA ESTO PARA DOCKER:
  server: {
    host: '0.0.0.0', // Permite que Docker acceda desde fuera del contenedor
    port: 5173,      // Fija el puerto estándar
    watch: {
      usePolling: true // Vital para que Windows/Docker detecten tus cambios al guardar
    }
  }
})