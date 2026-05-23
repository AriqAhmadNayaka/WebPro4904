import { StrictMode } from 'react' //untuk membungkus aplikasi React dengan StrictMode, yang membantu mendeteksi potensi masalah dalam aplikasi selama pengembangan dengan memberikan peringatan tambahan di konsol. StrictMode tidak mempengaruhi perilaku aplikasi di lingkungan produksi, tetapi sangat berguna untuk memastikan bahwa kode yang ditulis mengikuti praktik terbaik dan tidak mengandung masalah tersembunyi.
import { createRoot } from 'react-dom/client' //untuk membuat root DOM di mana aplikasi React akan dirender. Fungsi createRoot digunakan untuk menginisialisasi root DOM dan memungkinkan penggunaan fitur-fitur terbaru dari React, seperti concurrent mode, yang dapat meningkatkan performa aplikasi dengan cara yang lebih efisien dalam mengelola rendering dan pembaruan UI.
import './index.css' //untuk mengimpor file CSS utama yang berisi gaya global untuk aplikasi React. File index.css biasanya digunakan untuk mendefinisikan gaya dasar, seperti reset CSS, font, warna, dan gaya umum lainnya yang akan diterapkan ke seluruh aplikasi.
import App from './App.jsx' //untuk mengimpor komponen App yang merupakan komponen utama dari aplikasi React. Komponen App ini bertanggung jawab untuk mengatur routing dan menyediakan konteks autentikasi ke seluruh aplikasi, serta menjadi titik masuk utama untuk semua komponen lain yang akan dirender di dalamnya.

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
