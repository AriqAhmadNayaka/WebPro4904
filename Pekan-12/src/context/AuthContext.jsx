// Import React Context dan hook yang dibutuhkan untuk menyimpan status login secara global.
import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

// Context ini menjadi wadah bersama untuk data user, token, dan fungsi autentikasi.
const AuthContext = createContext(null);

// AuthProvider membungkus aplikasi agar semua child dapat mengakses status autentikasi.
export const AuthProvider = ({ children }) => {
    // State user menyimpan data profil yang diterima dari API setelah login.
    const [user, setUser] = useState(null);
    // Token diinisialisasi dari localStorage agar sesi tetap aktif setelah refresh halaman.
    const [token, setToken] = useState(localStorage.getItem('token'));
    // Loading dipakai untuk menunda route protection sampai pengecekan localStorage selesai.
    const [loading, setLoading] = useState(true);

    // Effect ini berjalan sekali saat provider pertama kali dipasang.
    useEffect(() => {
        // checkAuth membaca token dan user tersimpan untuk memulihkan sesi login.
        const checkAuth = async () => {
            const savedToken = localStorage.getItem('token');
            const savedUser = localStorage.getItem('user');

            // Jika localStorage lengkap, masukkan kembali data tersebut ke state React.
            if (savedToken && savedUser) {
                setToken(savedToken);
                setUser(JSON.parse(savedUser));
            }
            // Loading dimatikan setelah pengecekan selesai, baik data ditemukan maupun tidak.
            setLoading(false);
        };

        checkAuth();
    }, []);

    // Fungsi login mengirim email dan password ke API lalu menyimpan token jika berhasil.
    const login = async (email, password) => {
        try {
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            // Token dan data user disimpan di localStorage agar bertahan saat halaman direfresh.
            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            // State ikut diperbarui supaya UI langsung mengenali user sebagai sudah login.
            setToken(access_token);
            setUser(userData);

            return { success: true };
        } catch (error) {
            // Pesan error dari server diprioritaskan, lalu fallback ke pesan default.
            const message = error.response?.data?.message || 'Login gagal';
            return { success: false, message };
        }
    };

    // Fungsi logout mencoba memberi tahu server, lalu tetap membersihkan sesi di sisi client.
    const logout = async () => {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Pembersihan localStorage dan state tetap dilakukan walaupun request logout gagal.
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            setToken(null);
            setUser(null);
        }
    };

    // Object value berisi semua data dan aksi yang akan tersedia untuk consumer AuthContext.
    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token,
        login,
        logout,
    };

    // Provider meneruskan value autentikasi ke seluruh komponen anak.
    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

// Custom hook ini memudahkan komponen mengambil data AuthContext tanpa mengulang useContext.
export const useAuth = () => {
    const context = useContext(AuthContext);
    // Guard ini membantu mendeteksi pemakaian useAuth di luar AuthProvider.
    if (!context) {
        throw new Error('useAuth harus digunakan di dalam AuthProvider');
    }
    return context;
};
