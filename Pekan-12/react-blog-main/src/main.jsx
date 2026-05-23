import { StrictMode } from 'react' //mengimpor StrictMode dari React
import { createRoot } from 'react-dom/client' //mengimpor createRoot untuk merender aplikasi React
import './index.css' //mengimpor file CSS utama
import App from './App.jsx' //mengimpor komponen App

//merender aplikasi React ke elemen dengan id 'root'
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
