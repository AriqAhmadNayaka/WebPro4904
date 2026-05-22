import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Komponen pembungkus untuk melindungi route dari akses tanpa login
const ProtectedRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth();

    // Tunggu hingga status autentikasi selesai dicek
    if (loading) {
        return (
            <div className="loading-container">
                <div className="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    // Redirect ke login jika belum terautentikasi
    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    // Render halaman yang diminta jika sudah login
    return children;
};

export default ProtectedRoute;