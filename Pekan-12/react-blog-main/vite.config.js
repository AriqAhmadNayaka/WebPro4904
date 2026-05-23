import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: '/Pemrograman-Web/WebPro4904/Pekan12/react-blog-main/dist/',
  plugins: [react()],
  server: {
    proxy: {
      '/Pemrograman-Web/WebPro4904/Pekan-9': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
