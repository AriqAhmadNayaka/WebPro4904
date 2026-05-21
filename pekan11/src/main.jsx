import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'

// File main.jsx ini menjadi pintu masuk aplikasi React.
// React akan mencari elemen dengan id root di index.html, lalu menaruh komponen App di sana.
createRoot(document.getElementById('root')).render(
  // StrictMode membantu mengecek kode saat proses pengembangan.
  //  tampilan utama yang nanti muncul di browser.
  <StrictMode>
    <App />
  </StrictMode>,
)
