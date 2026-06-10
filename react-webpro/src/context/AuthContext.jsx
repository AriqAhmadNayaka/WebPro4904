import { createContext, useEffect, useState } from 'react';
import { authService } from '../services/api';

export const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const storedToken = localStorage.getItem('token');
    if (storedToken) {
      setToken(storedToken);
    }

    const bootstrap = async () => {
      if (!storedToken) {
        setLoading(false);
        return;
      }

      try {
        const result = await authService.me();
        setUser(result.user);
      } catch (error) {
        localStorage.removeItem('token');
        setToken(null);
        setUser(null);
      } finally {
        setLoading(false);
      }
    };

    bootstrap();
  }, []);

  const login = async (email, password) => {
    const result = await authService.login({ email, password });
    localStorage.setItem('token', result.token);
    setToken(result.token);
    setUser(result.user);
    return result;
  };

  const register = async (payload) => {
    const result = await authService.register(payload);
    localStorage.setItem('token', result.token);
    setToken(result.token);
    setUser(result.user);
    return result;
  };

  const logout = async () => {
    try {
      await authService.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      localStorage.removeItem('token');
      setToken(null);
      setUser(null);
    }
  };

  const refreshProfile = async () => {
    const result = await authService.me();
    setUser(result.user);
    return result.user;
  };

  const isAdmin = user?.role === 'admin';

  return <AuthContext.Provider value={{ user, token, loading, isAdmin, login, register, logout, refreshProfile, setUser }}>{children}</AuthContext.Provider>;
};
