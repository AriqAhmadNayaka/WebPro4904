import { createContext, useContext, useState, useEffect } from 'react';
import { authAPI } from '../services/api';

// Membuat React Context untuk menyimpan state autentikasi secara global
const AuthContext = createContext(null);

// Komponen Provider yang membungkus aplikasi dan menyediakan state autentikasi ke semua komponen anak
export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null); // Menyimpan data profil user yang sedang login
  const [token, setToken] = useState(localStorage.getItem('token')); // Menyimpan token JWT
  const [loading, setLoading] = useState(true); // Status pemuatan untuk mengecek session login saat awal aplikasi dimuat

  // Efek samping (Effect) untuk memeriksa apakah ada sesi login aktif di localStorage saat pertama kali aplikasi dijalankan
  useEffect(() => {
    const checkAuth = async () => {
      const savedToken = localStorage.getItem('token');
      const savedUser = localStorage.getItem('user');
      
      // Jika token dan profil user tersimpan di localStorage, sinkronkan ke state React
      if (savedToken && savedUser) {
        setToken(savedToken);
        setUser(JSON.parse(savedUser));
      }
      setLoading(false); // Selesai melakukan pengecekan sesi login
    };
    checkAuth();
  }, []);

  // Fungsi untuk menangani proses masuk log (login)
  const login = async (email, password) => {
    try {
      // Memanggil API login
      const response = await authAPI.login(email, password);
      
      const { access_token, user: userData } = response.data;
      
      // Menyimpan token dan data user ke localStorage agar sesi tetap bertahan saat halaman direfresh
      localStorage.setItem('token', access_token);
      localStorage.setItem('user', JSON.stringify(userData));
      
      // Memperbarui state autentikasi React
      setToken(access_token);
      setUser(userData);
      
      return { success: true };
    } catch (error) {
      // Menangkap pesan error dari backend atau memberikan pesan default jika gagal
      const message = error.response?.data?.message || 'Login gagal';
      return { success: false, message };
    }
  };

  // Fungsi untuk menangani proses keluar log (logout)
  const logout = async () => {
    try {
      // Memanggil API logout di backend untuk menonaktifkan token
      await authAPI.logout();
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Selalu bersihkan localStorage dan state React meskipun API logout gagal
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      setToken(null);
      setUser(null);
    }
  };

  // Menyusun value yang akan disebarkan ke seluruh aplikasi
  const value = {
    user,
    token,
    loading,
    isAuthenticated: !!token, // Status boolean apakah user sudah terautentikasi (!! mengonversi string token menjadi boolean)
    login,
    logout,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

// Custom hook kustom 'useAuth' untuk memudahkan pemanggilan AuthContext di komponen React lainnya
export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth harus digunakan di dalam AuthProvider');
  }
  return context;
};
