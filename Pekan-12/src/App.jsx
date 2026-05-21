// Komponen routing dari React Router untuk mengatur perpindahan halaman SPA.
import { BrowserRouter as Router, Navigate, Route, Routes } from 'react-router-dom'
// AuthProvider menyediakan data login untuk seluruh halaman.
import { AuthProvider } from './context/AuthContext'
// ProtectedRoute menjaga halaman post agar hanya bisa dibuka setelah login.
import ProtectedRoute from './components/ProtectedRoute'
// Halaman login untuk mendapatkan token dari API CI3.
import Login from './pages/Login'
// Halaman daftar post dari backend CodeIgniter Pekan-09.
import PostList from './pages/PostList'
// Halaman detail post berdasarkan ID.
import PostDetail from './pages/PostDetail'

function App() {
  return (
    // Provider diletakkan paling luar agar semua route dapat membaca status autentikasi.
    <AuthProvider>
      <Router>
        <Routes>
          {/* Route login terbuka tanpa token. */}
          <Route path="/login" element={<Login />} />

          {/* Route daftar post dilindungi token JWT dari API. */}
          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />

          {/* Route detail post mengambil ID dari URL /posts/:id. */}
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

          {/* Root aplikasi langsung diarahkan ke halaman posts sesuai modul. */}
          <Route path="/" element={<Navigate to="/posts" replace />} />

          {/* Route yang tidak dikenal diarahkan kembali ke posts. */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  )
}

export default App
