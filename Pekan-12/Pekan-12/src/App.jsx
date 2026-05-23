// import ini digunakan untuk mengimpor beberapa modul dan komponen yang diperlukan dalam aplikasi. 
// BrowserRouter, Routes, Route, dan Navigate diimpor dari react-router-dom untuk mengatur routing dalam aplikasi. 
// AuthProvider diimpor dari context/AuthContext untuk menyediakan konteks autentikasi ke seluruh aplikasi. ProtectedRoute adalah komponen yang digunakan untuk melindungi rute tertentu agar hanya dapat diakses oleh pengguna yang sudah login. 
// Login, PostList, dan PostDetail adalah halaman yang akan dirender berdasarkan rute yang ditentukan. Terakhir, index.css diimpor untuk memberikan styling global pada aplikasi.
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import Login from './pages/Login';
import PostList from './pages/PostList';
import PostDetail from './pages/PostDetail';
import './index.css';

// Fungsi App adalah komponen utama dari aplikasi yang mengatur struktur dan routing aplikasi. Di dalam fungsi ini, membungkus seluruh aplikasi dengan AuthProvider untuk menyediakan konteks autentikasi ke semua komponen di dalamnya. 
// Router digunakan untuk mengatur routing, dan Routes digunakan untuk mendefinisikan rute-rute yang ada dalam aplikasi. Setiap Route memiliki path dan element yang menentukan komponen mana yang akan dirender ketika pengguna mengakses rute tersebut. 
// ProtectedRoute digunakan untuk melindungi rute /posts dan /posts/:id agar hanya dapat diakses oleh pengguna yang sudah login. Jika pengguna mencoba mengakses rute yang tidak ada, mereka akan diarahkan ke halaman posts. 
// Fungsi App kemudian diekspor sebagai default export agar dapat digunakan di bagian lain aplikasi.
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

          {/* Rute default untuk mengarahkan pengguna ke halaman posts jika mereka mengakses root atau rute yang tidak ada. 
          Dengan menggunakan Navigate, memastikan bahwa pengguna akan diarahkan ke halaman yang benar jika mereka mencoba mengakses rute yang tidak valid. */}
          <Route path="/" element={<Navigate to="/posts" replace />} />

          {/* Rute wildcard untuk menangani semua rute yang tidak terdefinisi dan mengarahkan pengguna ke halaman posts. 
          Ini memastikan bahwa jika pengguna mencoba mengakses rute yang tidak ada, mereka akan diarahkan ke halaman utama daftar posts, 
          memberikan pengalaman pengguna yang lebih baik dan mencegah kesalahan 404. */}
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

// Terakhir, mengekspor fungsi App sebagai default export agar dapat digunakan di bagian lain aplikasi. 
// Dengan menggunakan export default, kita memungkinkan fungsi ini untuk diimpor dengan nama apa pun di file lain, 
// yang memberikan fleksibilitas dalam penggunaan komponen utama aplikasi ini di berbagai bagian aplikasi atau dalam pengujian.
export default App;