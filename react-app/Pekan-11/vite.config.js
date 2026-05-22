import { defineConfig } from 'vite'
import preact from '@preact/preset-vite'

// https://vite.dev/config/
export default defineConfig({
  plugins: [preact()],
  server: {
    host: 'localhost',
    port: 5173,
    proxy: {
      '/api': {
        target: 'http://localhost/WebPro4904/Pekan-9/index.php',
        changeOrigin: true,
      },
      '/uploads': {
        target: 'http://localhost/WebPro4904/Pekan-9',
        changeOrigin: true,
      },
    },
  },
})
