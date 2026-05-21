import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import PostList from './pages/PostList';
import PostDetail from './pages/PostDetail';
import './index.css';

// Komponen Aplikasi Utama
// Mendefinisikan penyedia state autentikasi (AuthProvider) dan struktur navigasi rute (react-router-dom)
function App() {
  return (
    // Membungkus seluruh aplikasi dengan AuthProvider agar state login dapat diakses di rute mana pun
    <AuthProvider>
      <Router>
        <Routes>
          {/* 
            Rute Login (Akses Publik)
            Menyajikan halaman form masuk bagi pengguna anonim 
          */}
          <Route path="/login" element={<Login />} />
          
          {/* 
            Rute Terproteksi (Akses Terbatas hanya untuk pengguna yang telah Login)
            Komponen target dibungkus di dalam <ProtectedRoute> untuk mencegah akses tidak sah.
          */}
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
          
          {/* 
            Pengalihan Rute Akar (Root Redirect)
            Jika pengguna mengakses '/' (halaman utama tanpa path), alihkan otomatis ke '/posts'.
            Jika sudah login, ProtectedRoute di '/posts' akan menampilkannya. Jika belum, akan dilempar ke '/login'.
          */}
          <Route path="/" element={<Navigate to="/posts" replace />} />
          
          {/* 
            Rute Pengalihan 404 (Catch-all Redirect)
            Menangkap semua path yang tidak dikenal/terdaftar di rute, lalu mengalihkannya secara aman ke '/posts'
          */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
