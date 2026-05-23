import { StrictMode } from 'react' // Mengimpor StrictMode untuk membantu mendeteksi potensi masalah dalam aplikasi
import { createRoot } from 'react-dom/client' // Mengimpor createRoot untuk merender aplikasi ke DOM
import './index.css' // Mengimpor file CSS untuk styling global aplikasi
import App from './App.jsx' // Mengimpor komponen utama App yang berisi logika dan tampilan aplikasi

createRoot(document.getElementById('root')).render( // Merender aplikasi ke elemen dengan id 'root' di index.html
  <StrictMode> 
    <App />
  </StrictMode>,
)
