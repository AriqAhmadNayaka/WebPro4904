// Import ini digunakan untuk mengimpor beberapa modul dan plugin yang diperlukan dalam konfigurasi Vite. defineConfig diimpor dari Vite untuk membantu dalam mendefinisikan konfigurasi Vite dengan cara yang lebih terstruktur. react diimpor dari @vitejs
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
// Konfigurasi Vite diekspor menggunakan defineConfig untuk memberikan struktur yang lebih baik dan dukungan untuk fitur-fitur Vite. Dalam konfigurasi ini, 
// menetapkan base path untuk aplikasi sebagai '/Pemrograman_Web/WebPro4904/Pekan-12/react-blog-main/dist/' agar aplikasi dapat diakses dengan benar ketika di-deploy. 
// Menambahkan plugin React untuk mendukung pengembangan aplikasi React dengan Vite. Selain itu, kita mengatur server proxy untuk mengarahkan permintaan API ke backend Laravel yang berjalan di localhost, sehingga kita dapat menghindari masalah CORS selama pengembangan.
export default defineConfig({
  base: '/Pemrograman_Web/WebPro4904/Pekan-12/react-blog-main/dist/',
  plugins: [react()],
  server: {
    // Konfigurasi proxy ini digunakan untuk mengarahkan permintaan API yang ditujukan ke '/Pemrograman_Web/WebPro4904/Pekan-09/ci3_project' ke target 'http://localhost'. 
    // Dengan menggunakan changeOrigin: true, kita memastikan bahwa header Origin dalam permintaan akan diubah agar sesuai dengan target, yang membantu dalam menghindari masalah CORS selama pengembangan. 
    // Ini memungkinkan aplikasi React untuk berkomunikasi dengan backend Laravel tanpa mengalami masalah lintas domain.
    proxy: {
       '/Pemrograman_Web/WebPro4904/Pekan-09/ci3_project': {
        // target ini digunakan untuk menentukan alamat backend Laravel yang akan menerima permintaan API. Dalam hal ini, kita mengarahkan permintaan ke 'http://localhost', yang merupakan alamat lokal tempat backend Laravel berjalan. 
        // Dengan mengatur target ini, kita memastikan bahwa semua permintaan yang ditujukan ke '/Pemrograman_Web/WebPro4904/Pekan-09/ci3_project' akan diteruskan ke backend Laravel yang sesuai.
        target: 'http://localhost',
        changeOrigin: true,
      },
    },
  },
})