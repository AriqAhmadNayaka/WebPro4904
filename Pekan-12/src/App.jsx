// App mengatur provider autentikasi dan seluruh konfigurasi route aplikasi React.
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import PostList from './pages/PostList';
import PostDetail from './pages/PostDetail';
import './index.css';

// Komponen root aplikasi membungkus routing dengan AuthProvider agar semua halaman bisa membaca status login.
function App() {
  return (
    // AuthProvider menyediakan user, token, login, logout, dan status loading ke seluruh route.
    <AuthProvider>
      {/* Router mengaktifkan navigasi client-side tanpa reload halaman penuh. */}
      <Router>
        {/* Routes memilih komponen halaman berdasarkan path URL saat ini. */}
        <Routes>
          {/* Route login tidak dilindungi agar user yang belum masuk tetap bisa mengaksesnya. */}
          <Route path="/login" element={<Login />} />

          {/* Route daftar post dilindungi supaya hanya user terautentikasi yang bisa melihatnya. */}
          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />
          {/* Route detail post juga dilindungi dan memakai parameter id dari URL. */}
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

          {/* Root aplikasi langsung diarahkan ke daftar post sebagai halaman utama. */}
          <Route path="/" element={<Navigate to="/posts" replace />} />

          {/* Route tidak dikenal diarahkan kembali ke daftar post agar user tidak berhenti di halaman kosong. */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

// Export default App agar bisa dirender oleh entry point React.
export default App;
