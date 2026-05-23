import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      '/Pemrograman_Web/WebPro4904/Pekan-09': {
        target: 'http://localhost',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/Pemrograman_Web\/WebPro4904\/Pekan-09/, '/Pemrograman_Web/WebPro4904/Pekan-09')
      }
    }
  }
})
