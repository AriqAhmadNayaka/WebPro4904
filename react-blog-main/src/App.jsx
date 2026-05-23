import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom'; //untuk mengatur routing dalam aplikasi React. Router digunakan untuk membungkus seluruh aplikasi dan memungkinkan penggunaan fitur routing, Routes digunakan untuk mendefinisikan kumpulan rute dalam aplikasi, Route digunakan untuk mendefinisikan rute individual dengan path dan komponen yang akan dirender ketika path tersebut diakses, dan Navigate digunakan untuk mengarahkan pengguna ke rute tertentu, seperti mengarahkan ke halaman login jika pengguna tidak terautentikasi atau mengarahkan ke halaman daftar postingan sebagai default.
import { AuthProvider } from './context/AuthContext'; //untuk menyediakan konteks autentikasi ke seluruh aplikasi React. AuthProvider akan membungkus seluruh aplikasi dan memungkinkan komponen-komponen di dalamnya untuk mengakses informasi tentang status autentikasi pengguna, token, dan fungsi-fungsi terkait seperti login dan logout melalui konteks AuthContext.
import ProtectedRoute from './components/ProtectedRoute'; //untuk membuat rute yang hanya dapat diakses oleh pengguna yang terautentikasi. ProtectedRoute akan memeriksa status autentikasi pengguna dari konteks AuthContext, dan jika pengguna tidak terautentikasi, mereka akan diarahkan ke halaman login. Jika pengguna sudah terautentikasi, komponen anak yang dibungkus oleh ProtectedRoute akan dirender, memungkinkan akses ke halaman yang dilind
import Login from './pages/Login'; //untuk mengimpor komponen Login yang akan digunakan sebagai halaman login dalam
import PostList from './pages/PostList'; //untuk mengimpor komponen PostList yang akan digunakan sebagai halaman daftar postingan dalam aplikasi React. Komponen ini bertanggung jawab untuk menampilkan daftar semua posts yang diambil dari API, serta mengelola status loading dan error selama proses pengambilan data.
import PostDetail from './pages/PostDetail'; //untuk mengimpor komponen PostDetail yang akan digunakan sebagai halaman detail post dalam aplikasi React. Komponen ini bertanggung jawab untuk menampilkan informasi detail tentang sebuah post, termasuk judul, artikel, penulis, tanggal pembuatan, dan gambar (jika tersedia). Komponen ini juga mengelola status loading dan error selama proses pengambilan data post dari API berdasarkan id yang diperoleh dari parameter URL.
import './index.css'; //untuk mengimpor file CSS utama yang berisi gaya global untuk aplikasi React. File index.css biasanya digunakan untuk mendefinisikan gaya dasar, seperti reset CSS, font, warna, dan gaya umum lainnya yang akan diterapkan ke seluruh aplikasi.

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

          <Route path="/" element={<Navigate to="/posts" replace />} />

          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App; //untuk mengekspor komponen App agar dapat digunakan di bagian lain dari aplikasi, terutama sebagai komponen utama yang dirender di index.js. Komponen App ini bertanggung jawab untuk mengatur routing dan menyediakan konteks autentikasi ke seluruh aplikasi React.
