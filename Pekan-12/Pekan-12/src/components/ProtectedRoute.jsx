// Komponen ProtectedRoute digunakan untuk melindungi rute tertentu dalam aplikasi yang hanya boleh diakses oleh pengguna yang sudah terautentikasi. 
// Komponen ini memeriksa status autentikasi pengguna melalui konteks AuthContext dan menampilkan konten yang sesuai berdasarkan status tersebut. 
// Jika pengguna sedang memuat status autentikasi, komponen akan menampilkan indikator loading. Jika pengguna tidak terautentikasi, mereka akan diarahkan ke halaman login. 
// Jika pengguna terautentikasi, komponen akan menampilkan konten anak yang diberikan sebagai prop.
import { Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Dalam komponen ProtectedRoute, kita menggunakan useAuth untuk mendapatkan status autentikasi pengguna dan status loading. 
// Berdasarkan status ini, kita menentukan apa yang akan ditampilkan kepada pengguna. 
// Jika aplikasi masih memuat status autentikasi, kita menampilkan indikator loading. Jika pengguna tidak terautentikasi, kita mengarahkan mereka ke halaman login. Jika
const ProtectedRoute = ({ children }) => {
    const { isAuthenticated, loading } = useAuth();

    // Bagian return dari komponen ProtectedRoute berisi logika untuk menentukan apa yang akan ditampilkan kepada pengguna berdasarkan status autentikasi dan loading. 
    // Jika aplikasi masih memuat status autentikasi, kita menampilkan indikator loading. Jika pengguna tidak terautentikasi, kita mengarahkan mereka ke halaman login menggunakan komponen Navigate dari react-router-dom. 
    // Jika pengguna terautentikasi, kita menampilkan konten anak yang diberikan sebagai prop.
    if (loading) {
        return (
            <div className="loading-container">
                <div className="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        );
    }

    // Jika pengguna tidak terautentikasi, kita mengarahkan mereka ke halaman login menggunakan komponen Navigate dari react-router-dom. 
    // Properti replace digunakan untuk memastikan bahwa pengguna tidak dapat kembali ke halaman sebelumnya setelah diarahkan ke halaman login.
    if (!isAuthenticated) {
        return <Navigate to="/login" replace />;
    }

    // Jika pengguna terautentikasi, kita menampilkan konten anak yang diberikan sebagai prop. 
    // Konten ini akan menjadi komponen atau halaman yang ingin kita lindungi dengan rute ini.
    return children;
};

// Ekspor komponen ProtectedRoute agar dapat digunakan di bagian lain aplikasi, 
// seperti di App.jsx untuk melindungi rute tertentu yang hanya boleh diakses oleh pengguna yang sudah terautentikasi.
export default ProtectedRoute;