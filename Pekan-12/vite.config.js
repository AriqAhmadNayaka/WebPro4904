import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  // Base URL saat build, sesuaikan dengan path folder dist di server
  base: '/Pemograman_Web/WebPro4904/Pekan-12/dist/',
  plugins: [react()],
  server: {
    proxy: {
      // Proxy request API ke localhost agar menghindari CORS saat development
      '/Pemograman_Web/WebPro4904/Pekan-09': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
