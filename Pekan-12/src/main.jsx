// StrictMode membantu menampilkan peringatan React saat proses development.
import { StrictMode } from 'react'
// createRoot adalah API React DOM modern untuk merender aplikasi ke elemen HTML.
import { createRoot } from 'react-dom/client'
// File CSS utama berisi Tailwind dan style global aplikasi.
import './index.css'
// Komponen App menjadi pintu masuk seluruh routing aplikasi.
import App from './App.jsx'

// Menghubungkan aplikasi React ke elemen <div id="root"> di index.html.
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
