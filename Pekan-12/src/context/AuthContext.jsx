import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

// Membuat context untuk menyimpan state autentikasi secara global
const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    // Ambil token dari localStorage saat pertama kali load
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [loading, setLoading] = useState(true);

    // Cek sesi login yang tersimpan saat aplikasi pertama dibuka
    useEffect(() => {
        const checkAuth = async () => {
            const savedToken = localStorage.getItem('token');
            const savedUser = localStorage.getItem('user');

            if (savedToken && savedUser) {
                setToken(savedToken);
                setUser(JSON.parse(savedUser));
            }
            setLoading(false);
        };

        checkAuth();
    }, []);

    const login = async (email, password) => {
        try {
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            // Simpan token dan data user ke localStorage agar sesi bertahan
            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            setToken(access_token);
            setUser(userData);

            return { success: true };
        } catch (error) {
            const message = error.response?.data?.message || 'Login gagal';
            return { success: false, message };
        }
    };

    const logout = async () => {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Hapus sesi dari localStorage dan reset state meski request gagal
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            setToken(null);
            setUser(null);
        }
    };

    // Nilai yang dibagikan ke seluruh komponen yang menggunakan context ini
    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token, // true jika token ada
        login,
        logout,
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

// Custom hook untuk mengakses AuthContext dengan validasi penggunaan
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth harus digunakan di dalam AuthProvider');
    }
    return context;
};