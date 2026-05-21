import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Komponen Pembungkus (Wrapper) Rute untuk Melindungi Halaman Sensitif.
// Hanya pengguna yang sudah terautentikasi (login) yang diizinkan untuk melihat komponen di dalamnya (children).
const ProtectedRoute = ({ children }) => {
    // Mengambil status autentikasi dan status loading dari AuthContext.
    const { isAuthenticated, loading } = useAuth();

    // Jika sistem masih memeriksa status token/sesi, tampilkan indikator loading.
    if (loading) {
        return (
            <div className="loading-container">
                <div className="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    // Jika pengguna tidak terautentikasi (belum login), alihkan rute ke halaman login secara paksa.
    // Properti `replace` digunakan agar halaman saat ini digantikan dalam riwayat navigasi (history stack).
    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    // Jika terautentikasi, tampilkan komponen anak yang seharusnya ditampilkan.
    return children;
};

export default ProtectedRoute;
