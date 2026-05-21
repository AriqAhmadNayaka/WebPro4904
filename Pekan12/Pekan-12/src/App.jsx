import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import ProtectedRoute from "./components/ProtectedRoute";
import Login from "./pages/Login";
import PostList from "./pages/PostList";
import PostDetail from "./pages/PostDetail";
import "./index.css";

function App() {
  return (
    // AuthProvider membungkus seluruh aplikasi agar data login bisa dipakai semua halaman.
    <AuthProvider>
      <Router>
        <Routes>
          {/* Halaman login bisa diakses tanpa autentikasi. */}
          <Route path="/login" element={<Login />} />

          {/* Halaman posts hanya bisa dibuka setelah user berhasil login. */}
          <Route
            path="/posts"
            element={
              <ProtectedRoute>
                <PostList />
              </ProtectedRoute>
            }
          />

          {/* Halaman detail membaca id post dari parameter URL. */}
          <Route
            path="/posts/:id"
            element={
              <ProtectedRoute>
                <PostDetail />
              </ProtectedRoute>
            }
          />

          {/* Redirect halaman utama dan route tidak dikenal ke daftar posts. */}
          <Route path="/" element={<Navigate to="/posts" replace />} />
          <Route path="*" element={<Navigate to="/posts" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}
export default App;
