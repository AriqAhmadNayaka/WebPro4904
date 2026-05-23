//mengimpor komponen routing dari react-router-dom
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
//mengimpor AuthProvider untuk mengatur autentikasi user
import { AuthProvider } from './context/AuthContext';
//mengimpor ProtectedRoute untuk melindungi halaman tertentu
import ProtectedRoute from './components/ProtectedRoute';
//mengimpor halaman Login
import Login from './pages/Login';
//mengimpor halaman daftar post
import PostList from './pages/PostList';
//mengimpor halaman detail post
import PostDetail from './pages/PostDetail';
//mengimpor file CSS utama
import './index.css';

function App() { //fungsi utama aplikasi React
  return ( 
    //membungkus aplikasi dengan AuthProvider untuk autentikasi
    <AuthProvider> 
      <Router>
        <Routes>
          {/* route halaman login */ }
          <Route path="/login" element={<Login />} />

          {/* Route halaman daftar post yang dilindungi login */}
          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />
          {/* Route detail post berdasarkan ID */}
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

          {/* Redirect halaman utama ke /posts */}
          <Route path="/" element={<Navigate to="/posts" replace />} /> 
          {/* Redirect jika route tidak ditemukan */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
