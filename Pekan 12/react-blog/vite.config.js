import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  // Menentukan base path build agar menggunakan path relatif (memudahkan pemindahan folder dist ke htdocs)
  base: './',
  plugins: [react()],
  // Konfigurasi server pengembangan (development server) Vite
  server: {
    // Proxy digunakan untuk mengarahkan request API dari frontend ke server backend lokal (XAMPP/Apache)
    // Hal ini berguna untuk menghindari kendala keamanan CORS (Cross-Origin Resource Sharing) saat pengembangan
    proxy: {
      '/ci3_project': {
        target: 'http://localhost/PemrogramanWeb/WebPro4904/Pekan 12', // Path server backend lokal (XAMPP/Apache)
        changeOrigin: true, // Mengubah origin header request agar sesuai dengan target
      },
    },
  },
})
