import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  base: './',
  plugins: [react()],
  server: {
    proxy: {
      '/Pemrograman%20Web/WebPro4904/Pekan-09/ci3_project': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
