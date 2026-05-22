import { useState } from 'react';
import { AuthContext } from './auth';
import { authAPI } from '../service/api';

export const AuthProvider = ({ children }) => {
  const savedUser = localStorage.getItem('user');
  const [user, setUser] = useState(() => {
    try {
      return savedUser ? JSON.parse(savedUser) : null;
    } catch {
      localStorage.removeItem('user');
      return null;
    }
  });
  const [token, setToken] = useState(localStorage.getItem('token'));
  const [loading] = useState(false);

  const login = async (email, password) => {
    try {
      const response = await authAPI.login(email, password);
      const payload = response.data;
      const accessToken = payload.access_token || payload.token || payload.data?.access_token;
      const userData = payload.user || payload.data || { email };

      if (!accessToken) {
        return { success: false, message: 'Token tidak ditemukan pada response login.' };
      }

      localStorage.setItem('token', accessToken);
      localStorage.setItem('user', JSON.stringify(userData));
      setToken(accessToken);
      setUser(userData);

      return { success: true };
    } catch (error) {
      const message = error.response?.data?.message || 'Login gagal.';
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

  return (
    <AuthContext.Provider value={{ user, token, loading, isAuthenticated: Boolean(token), login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
