//menghubungkan semua komponen dan halaman, serta mengatur routing menggunakan React Router
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import PostList from './pages/PostList';
import PostDetail from './pages/PostDetail';
import './index.css';

// Komponen utama aplikasi yang mengatur routing dan konteks otentikasi
function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />

          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

// Redirect root path dan wildcard path ke /posts
          <Route path="/" element={<Navigate to="/posts" replace />} />
// Redirect wildcard path ke /posts
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

// Ekspor komponen App sebagai default export
export default App;
