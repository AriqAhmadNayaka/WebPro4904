import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'

// Render aplikasi ke elemen #root di index.html
// StrictMode membantu mendeteksi potensi masalah saat development
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
