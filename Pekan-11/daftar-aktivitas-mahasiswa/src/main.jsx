import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './App.jsx';
import './styles.css';

// Menghubungkan React ke elemen <div id="root"> yang ada di index.html.
createRoot(document.getElementById('root')).render(
  // StrictMode membantu menandai potensi masalah saat aplikasi dikembangkan.
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
