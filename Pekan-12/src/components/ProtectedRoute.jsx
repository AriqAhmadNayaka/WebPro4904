// ProtectedRoute memakai Navigate untuk mengalihkan user yang belum login.
import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Komponen ini membungkus halaman yang hanya boleh diakses setelah autentikasi.
const ProtectedRoute = ({ children }) => {
    // Status autentikasi dan loading diambil dari AuthContext global.
    const { isAuthenticated, loading } = useAuth();

    // Saat status login masih dicek, tampilkan indikator loading agar route tidak langsung dialihkan.
    if (loading) {
        return (
            <div className="loading-container">
                <div className="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    // Jika user belum login, arahkan ke halaman login dan ganti history route saat ini.
    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    // Jika sudah login, tampilkan halaman anak yang dibungkus ProtectedRoute.
    return children;
};

// Export default agar route di App.jsx bisa memakai pelindung autentikasi ini.
export default ProtectedRoute;
