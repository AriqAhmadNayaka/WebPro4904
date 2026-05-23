import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'

//menghubungkan react dengan elemen HTML
createRoot(document.getElementById('root')).render(
  <StrictMode>
    {/*menampilkan component utama aplikasi*/}
    <App />
  </StrictMode>,
)
