// StrictMode membantu mendeteksi potensi masalah React saat aplikasi berjalan di mode development.
import { StrictMode } from 'react'
// createRoot dipakai React 18+ untuk memasang aplikasi ke elemen HTML root.
import { createRoot } from 'react-dom/client'
// Import CSS global agar Tailwind dan style dasar aplikasi aktif di seluruh halaman.
import './index.css'
// App adalah komponen utama yang berisi provider dan konfigurasi route aplikasi.
import App from './App.jsx'

// Render aplikasi React ke elemen <div id="root"></div> yang ada di file HTML.
createRoot(document.getElementById('root')).render(
  // StrictMode membungkus App untuk memberi peringatan tambahan selama pengembangan.
  <StrictMode>
    <App />
  </StrictMode>,
)
