import { Navigate, useLocation } from 'react-router-dom'
import { useAuth } from '../context/useAuth'

function ProtectedRoute({ children }) {
  const { isAuthenticated } = useAuth()
  const location = useLocation()

  // Jika belum login, user dikirim ke /login dan lokasi asalnya disimpan.
  if (!isAuthenticated) {
    return <Navigate to="/login" replace state={{ from: location }} />
  }

  // Jika sudah login, tampilkan halaman yang diminta.
  return children
}

export default ProtectedRoute
