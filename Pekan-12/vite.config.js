import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  // Base relatif membuat hasil build di folder dist tetap bisa dibuka dari htdocs/subfolder.
  base: './',
  // Plugin React dipakai agar Vite dapat membaca JSX dan fitur React Fast Refresh.
  plugins: [react()],
  server: {
    proxy: {
      // Proxy ini meneruskan request API ke project CodeIgniter 3 Pekan-09 di XAMPP.
      '/Pemrograman%20Web/WebPro4904/Pekan-09/ci3_project': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
