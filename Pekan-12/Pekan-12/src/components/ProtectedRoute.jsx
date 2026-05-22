import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/auth';

const ProtectedRoute = ({ children }) => {
  const { isAuthenticated, loading } = useAuth();

  if (loading) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center bg-gray-100 text-gray-700">
        <div className="mb-4 h-12 w-12 animate-spin rounded-full border-b-2 border-blue-600" />
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
