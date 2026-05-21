import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

// Membuat Context baru untuk Autentikasi dengan nilai default null.
const AuthContext = createContext(null);

// Komponen Provider yang akan membungkus seluruh aplikasi agar state autentikasi bisa diakses secara global.
export const AuthProvider = ({ children }) => {
    // State untuk menyimpan informasi pengguna yang sedang login.
    const [user, setUser] = useState(null);
    // State untuk menyimpan token autentikasi (diambil langsung dari localStorage saat pertama kali dimuat).
    const [token, setToken] = useState(localStorage.getItem('token'));
    // State untuk menunjukkan apakah proses pemulihan/pengecekan sesi autentikasi sedang berjalan.
    const [loading, setLoading] = useState(true);

    // Efek samping (side-effect) yang berjalan sekali ketika komponen pertama kali dimuat.
    useEffect(() => {
        const checkAuth = async () => {
            const savedToken = localStorage.getItem('token');
            const savedUser = localStorage.getItem('user');

            // Jika token dan data pengguna ditemukan di localStorage, pulihkan ke state.
            if (savedToken && savedUser) {
                setToken(savedToken);
                setUser(JSON.parse(savedUser));
            }
            // Selesai melakukan pengecekan sesi.
            setLoading(false);
        };

        checkAuth();
    }, []);

    // Fungsi asinkronus untuk menangani proses login pengguna.
    const login = async (email, password) => {
        try {
            setLoading(true);
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            // Menyimpan token dan data user ke localStorage agar sesi tetap bertahan meski halaman dimuat ulang.
            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            // Memperbarui status state global.
            setToken(access_token);
            setUser(userData);

            return { success: true };
        } catch (error) {
            // Menangkap pesan kesalahan dari server atau memberikan pesan default.
            const message = error.response?.data?.message || 'Login gagal';
            return { success: false, message };
        } finally {
            setLoading(false);
        }
    };

    // Fungsi asinkronus untuk menangani proses logout pengguna.
    const logout = async () => {
        try {
            // Memanggil API logout di sisi backend (untuk membatalkan token/session di server).
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Menghapus data sesi dari localStorage.
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            // Mereset status state global kembali ke null.
            setToken(null);
            setUser(null);
        }
    };

    // Objek value yang akan dibagikan ke seluruh komponen anak yang mengkonsumsi context ini.
    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token, // Mengubah keberadaan token menjadi boolean (true jika ada token, false jika tidak).
        login,
        logout,
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

// Custom hook helper agar komponen lain dapat menggunakan context ini dengan mudah dan aman.
export const useAuth = () => {
    const context = useContext(AuthContext);
    // Memastikan hook ini dipanggil di dalam komponen yang dibungkus oleh AuthProvider.
    if (!context) {
        throw new Error('useAuth harus digunakan di dalam AuthProvider');
    }
    return context;
};
