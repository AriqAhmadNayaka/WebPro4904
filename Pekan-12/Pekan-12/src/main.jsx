// Import ini digunakan untuk mengimpor beberapa modul dan komponen yang diperlukan dalam aplikasi. 
// StrictMode diimpor dari react untuk membantu mendeteksi masalah potensial dalam aplikasi selama pengembangan. 
// createRoot diimpor dari react-dom/client untuk membuat root DOM tempat aplikasi akan dirender. 
// index.css diimpor untuk memberikan styling global pada aplikasi, dan App adalah komponen utama yang akan dirender sebagai bagian dari aplikasi.
import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'

// Fungsi createRoot digunakan untuk membuat root DOM tempat aplikasi akan dirender. Dengan menggunakan createRoot, 
// dapat memanfaatkan fitur-fitur terbaru dari React, seperti concurrent mode, yang memungkinkan aplikasi untuk merender dengan lebih efisien dan responsif. 
// Setelah root dibuat, memanggil metode render untuk merender komponen App di dalam StrictMode. StrictMode adalah alat pengembangan yang membantu mendeteksi masalah potensial dalam aplikasi, 
// seperti penggunaan API yang sudah usang atau efek samping yang tidak diinginkan. 
// Dengan membungkus App di dalam StrictMode, dapat memastikan bahwa aplikasi kita lebih stabil dan mudah untuk dipelihara selama pengembangan.
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)