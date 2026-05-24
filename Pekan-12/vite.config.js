import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  base: './pemrograman_web/WebPro4904/Pekan-12/dist/', // Sesuaikan dengan path yang digunakan di server
  plugins: [react()],
  server: {
    proxy: {
      '/pemrograman_web/WebPro4904/Pekan-09': { // Sesuaikan dengan path API yang digunakan di src/services/api.js
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
