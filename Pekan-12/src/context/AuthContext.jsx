import { useState, useEffect } from 'react';
import { authAPI } from '../service/api';
import AuthContext from './AuthContextCore';

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(localStorage.getItem('token'));
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const checkAuth = async () => {
            try {
                const savedToken = localStorage.getItem('token');
                const savedUser = localStorage.getItem('user');

                if (savedToken && savedUser) {
                    setToken(savedToken);
                    setUser(JSON.parse(savedUser));
                } else {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    setToken(null);
                    setUser(null);
                }
            } catch (error) {
                console.error('Auth data tidak valid:', error);
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                setToken(null);
                setUser(null);
            } finally {
                setLoading(false);
            }
        };
        checkAuth();
    }, []);

    const login = async (email, password) => {
        try {
            const response = await authAPI.login(email, password);
            const { access_token, data: userData } = response.data;

            if (!access_token || !userData) {
                return { success: false, message: 'Response login tidak valid' };
            }

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
