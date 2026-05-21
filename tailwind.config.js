import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import { BrowserRouter } from 'react-router-dom'
import './index.css'
import App from './App.jsx'

// File ini adalah titik awal React untuk memasang App ke elemen #root di index.html.
createRoot(document.getElementById('root')).render(
  <StrictMode>
    {/* BrowserRouter mengaktifkan URL seperti /login, /posts, dan /posts/:id. */}
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </StrictMode>,
)
