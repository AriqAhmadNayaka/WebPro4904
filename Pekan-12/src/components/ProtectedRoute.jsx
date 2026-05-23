import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const ProtectedRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth();

    // Selama status login masih dicek, tampilkan loading singkat dulu.
    if (loading) {
        return (
            <div className="loading-container">
             <div className="loading-spinner"></div>
             <p>Loading...</p> </div>
        );
    }

    // Jika belum login, user langsung dikembalikan ke halaman login.
    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    return children;
};
export default ProtectedRoute;
