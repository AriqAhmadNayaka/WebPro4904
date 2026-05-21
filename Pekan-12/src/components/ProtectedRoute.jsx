// Navigate dipakai untuk memindahkan user yang belum login ke halaman /login.
import { Navigate } from 'react-router-dom'
// useAuth membaca status autentikasi dari AuthContext.
import { useAuth } from '../context/AuthContext'

// ProtectedRoute membungkus halaman yang membutuhkan token.
const ProtectedRoute = ({ children }) => {
  // isAuthenticated bernilai true bila token tersedia di state/localStorage.
  const { isAuthenticated, loading } = useAuth()

  // Saat AuthContext masih mengecek localStorage, tampilkan spinner sederhana.
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-100 flex flex-col items-center justify-center gap-4">
        <div className="h-12 w-12 animate-spin rounded-full border-4 border-blue-100 border-b-blue-600"></div>
        <p className="text-sm font-medium text-gray-600">Loading...</p>
      </div>
    )
  }

  // Jika token tidak ada, user diarahkan ke login.
  if (!isAuthenticated) {
    return <Navigate to="/login" replace />
  }

  // Jika sudah login, halaman asli ditampilkan.
  return children
}

// Export default agar komponen mudah diimport di App.jsx.
export default ProtectedRoute
