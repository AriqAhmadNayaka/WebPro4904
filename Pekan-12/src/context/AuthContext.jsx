import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../service/Api';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    // State auth disimpan di context supaya tidak perlu kirim props ke banyak komponen.
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            // Saat halaman direfresh, data login diambil lagi dari localStorage.
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
            // Login dikirim ke API CI3, lalu token dan data user disimpan untuk sesi berikutnya.
            const response = await authAPI.login(email, password);

            const {
                access_token,
                data: userData,
            } = response.data;

            localStorage.setItem('token', access_token);
            localStorage.setItem('user', JSON.stringify(userData));

            setToken(access_token);
            setUser(userData);

            return { success: true };

        } catch (error) {
            const message =
                error.response?.data?.message || 'Login gagal';

            return {
                success: false,
                message,
            };
        }
    };

    const logout = async () => {
        try {
            await authAPI.logout();

        } catch (error) {
            console.error('Logout error:', error);

        } finally {
            // Data lokal tetap dibersihkan walaupun request logout ke server gagal.
            localStorage.removeItem('token');
            localStorage.removeItem('user');

            setToken(null);
            setUser(null);
        }
    };

    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!token,
        login,
        logout,
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error(
            'useAuth harus digunakan di dalam AuthProvider'
        );
    }

    return context;
};
