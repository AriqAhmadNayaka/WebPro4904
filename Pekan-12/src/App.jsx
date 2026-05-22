import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import PostList from './pages/PostList';
import PostDetail from './pages/PostDetail';
import './index.css';

function App() {
  return (
    // AuthProvider membungkus seluruh app agar state login bisa diakses di mana saja
    <AuthProvider>
      <Router>
        <Routes>
          {/* Halaman login, bisa diakses tanpa autentikasi */}
          <Route path="/login" element={<Login />} />

          {/* Halaman daftar post, hanya bisa diakses setelah login */}
          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />

          {/* Halaman detail post, :id diisi dengan ID post yang dipilih */}
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

          {/* Redirect root ke /posts */}
          <Route path="/" element={<Navigate to="/posts" replace />} />

          {/* Redirect semua route yang tidak dikenal ke /posts */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;