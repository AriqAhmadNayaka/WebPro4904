import { StrictMode } from 'react' // Pastikan untuk mengimpor StrictMode dari React
import { createRoot } from 'react-dom/client' // Pastikan untuk mengimpor createRoot dari react-dom/client
import './index.css' // Pastikan untuk membuat file index.css jika belum ada
import App from './App.jsx' // Pastikan untuk membuat file App.jsx jika belum ada

// Render aplikasi React ke dalam elemen dengan id 'root'
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
