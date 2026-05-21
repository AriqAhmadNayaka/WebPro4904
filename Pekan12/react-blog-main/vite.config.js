import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: './',
  plugins: [react()],
  server: {
    proxy: {
      '/PemprogramanWeb/WebPro4904/Pekan-9': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
