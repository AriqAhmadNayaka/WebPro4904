import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Komponen ProtectedRoute bertindak sebagai penjaga rute (Route Guard).
// Hanya mengizinkan akses ke komponen anak (children) jika pengguna sudah login.
const ProtectedRoute = ({ children }) => {
  // Mengambil status autentikasi dan loading dari AuthContext
  const { isAuthenticated, loading } = useAuth();
  
  // Jika masih dalam proses memuat (misalnya mengecek token di local storage saat awal reload halaman)
  if (loading) {
    return (
      <div className="loading-container">
        {/* Spinner pemuatan yang bergaya premium, didefinisikan di index.css */}
        <div className="loading-spinner"></div>
        <p className="mt-4 text-gray-500 font-medium animate-pulse">Loading...</p>
      </div>
    );
  }
  
  // Jika pengguna tidak terautentikasi (belum login), alihkan rute ke halaman login
  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }
  
  // Jika sudah terautentikasi, tampilkan komponen anak yang dilindungi
  return children;
};

export default ProtectedRoute;
