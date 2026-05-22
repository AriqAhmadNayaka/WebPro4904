// defineConfig membantu Vite membaca konfigurasi dengan dukungan tipe dan plugin.
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// Konfigurasi utama Vite untuk build React dan proxy backend lokal.
export default defineConfig({
  // Base path disesuaikan dengan lokasi project saat hasil build dijalankan dari folder XAMPP.
  base: '/Pemrograman_Web/WebPro4904/Pekan-12/dist/',
  // Plugin React mengaktifkan transform JSX dan fitur React Fast Refresh saat development.
  plugins: [react()],
  // Server development memakai proxy agar request frontend ke backend lokal tidak terkena masalah origin.
  server: {
    proxy: {
      // Path backend Pekan-09 diteruskan ke localhost tempat Laravel/XAMPP berjalan.
      '/Pemrograman_Web/WebPro4904/Pekan-09': {
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})
