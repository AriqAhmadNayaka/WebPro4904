import { Navigate } from 'react-router-dom'; //untuk mengarahkan pengguna ke halaman login 
// jika mereka tidak terautentikasi
import { useAuth } from '../context/AuthContext'; 
//untuk mengakses status autentikasi pengguna dari konteks AuthContext

const ProtectedRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth();

    if (loading) {
        return (
            <div className="loading-container">
                <div className="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    return children;
};

export default ProtectedRoute; 
//untuk mengekspor komponen ProtectedRoute agar dapat digunakan di bagian lain
//  dari aplikasi, terutama untuk membungkus rute yang memerlukan autentikasi pengguna.
