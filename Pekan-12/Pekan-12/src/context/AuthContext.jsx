// AuthContext.jsx adalah file yang berfungsi sebagai konteks untuk mengelola autentikasi pengguna dalam aplikasi React. 
// Dalam file ini, kita membuat konteks menggunakan createContext dan menyediakan fungsi login dan logout yang dapat digunakan di seluruh aplikasi. 
// Juga menyimpan informasi pengguna dan token autentikasi di localStorage untuk menjaga sesi pengguna tetap aktif bahkan setelah halaman direfresh. 
// Selain itu, kita menggunakan useEffect untuk memeriksa status autentikasi saat aplikasi dimuat dan memperbarui state sesuai dengan informasi yang ditemukan di localStorage. 
// Dengan menggunakan AuthProvider, kita dapat membungkus seluruh aplikasi atau bagian tertentu dari aplikasi untuk memberikan akses ke konteks autentikasi ini, dan dengan useAuth, kita dapat dengan mudah mengakses status autentikasi dan fungsi login/logout di komponen mana pun dalam aplikasi.
import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

// Membuat konteks autentikasi dengan createContext. Ini akan digunakan untuk menyediakan informasi autentikasi dan fungsi login/logout ke seluruh aplikasi.
const AuthContext = createContext(null);

// AuthProvider adalah komponen yang membungkus bagian aplikasi yang membutuhkan akses ke konteks autentikasi. 
// Di dalam AuthProvider, kita mengelola state untuk pengguna, token, dan status loading. 
// Kita juga mendefinisikan fungsi login dan logout yang akan digunakan untuk mengelola sesi pengguna.
export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [loading, setLoading] = useState(true);

    // useEffect digunakan untuk memeriksa status autentikasi saat aplikasi dimuat. 
    // Kita mencoba mengambil token dan informasi pengguna dari localStorage. 
    // Jika ditemukan, kita memperbarui state dengan informasi tersebut. Setelah pemeriksaan selesai, kita mengatur loading menjadi false untuk menunjukkan bahwa proses autentikasi telah selesai.
    useEffect(() => {
        const checkAuth = async () => {
            const savedToken = localStorage.getItem('token');
            const savedUser = localStorage.getItem('user');

            // Jika token dan informasi pengguna ditemukan di localStorage, 
            // kita memperbarui state dengan informasi tersebut. Ini memungkinkan pengguna tetap masuk bahkan setelah halaman direfresh.
            if (savedToken && savedUser) {
                setToken(savedToken);
                setUser(JSON.parse(savedUser));
            }
            setLoading(false);
        };

        // Memanggil fungsi checkAuth untuk memeriksa status autentikasi saat aplikasi dimuat.
        checkAuth();
    }, []);

    // Fungsi login digunakan untuk mengirim permintaan login ke API dengan email dan password yang diberikan. 
    // Jika login berhasil, kita menyimpan token dan informasi pengguna di localStorage dan memperbarui state dengan informasi tersebut. 
    // Jika login gagal, kita menangkap error dan mengembalikan pesan error yang sesuai.
    const login = async (email, password) => {
        // Mencoba melakukan login dengan mengirim permintaan ke API menggunakan authAPI.login. 
        // Jika berhasil, kita menyimpan token dan informasi pengguna di localStorage dan memperbarui state. 
        // Jika gagal, kita menangkap error dan mengembalikan pesan error yang sesuai.
        try {
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            setToken(access_token);
            setUser(userData);

            return { success: true };
        // Jika terjadi error selama proses login, kita menangkap error tersebut dan mengembalikan pesan error yang sesuai. 
        // Pesan error ini dapat digunakan di komponen login untuk memberikan umpan balik kepada pengguna tentang kegagalan login.
        } catch (error) {
            const message = error.response?.data?.message || 'Login gagal';
            return { success: false, message };
        }
    };

    // Fungsi logout digunakan untuk menghapus sesi pengguna dengan memanggil API logout dan membersihkan token serta informasi pengguna dari localStorage dan state. 
    // Ini memastikan bahwa pengguna keluar dari aplikasi dan tidak dapat mengakses konten yang dilindungi tanpa login kembali.
    const logout = async () => {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            setToken(null);
            setUser(null);
        }
    };

    // Membuat objek value yang berisi informasi pengguna, token, status loading, status autentikasi, dan fungsi login/logout. 
    // Objek ini akan disediakan ke seluruh aplikasi melalui AuthContext.Provider. 
    // Status autentikasi dihitung berdasarkan keberadaan token, sehingga jika token ada, isAuthenticated akan bernilai true, dan jika tidak ada, isAuthenticated akan bernilai false.
    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token,
        login,
        logout,
    };

    // Mengembalikan AuthContext.Provider yang membungkus children dengan value yang telah dibuat. 
    // Ini memungkinkan komponen anak untuk mengakses informasi autentikasi dan fungsi login/logout melalui konteks ini.
    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

// Custom hook useAuth digunakan untuk memudahkan akses ke konteks autentikasi di komponen lain. 
// Hook ini memanggil useContext dengan AuthContext untuk mendapatkan nilai dari konteks. 
// Jika hook ini digunakan di luar AuthProvider, kita melempar error untuk memberi tahu pengembang bahwa mereka harus menggunakan hook ini di dalam AuthProvider. 
// Dengan menggunakan useAuth, kita dapat dengan mudah mengakses status autentikasi, informasi pengguna, dan fungsi login/logout di komponen mana pun dalam aplikasi tanpa harus secara eksplisit mengimpor dan menggunakan useContext setiap kali.
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth harus digunakan di dalam AuthProvider');
    }
    return context;
};