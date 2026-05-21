/* eslint-disable react-refresh/only-export-components */
// Context React dipakai agar data login bisa diakses dari banyak komponen tanpa prop drilling.
import { createContext, useContext, useEffect, useState } from 'react'
// authAPI berisi function request ke endpoint auth CodeIgniter.
import { authAPI } from '../services/api'

// AuthContext menyimpan user, token, loading, login, dan logout.
const AuthContext = createContext(null)

// AuthProvider membungkus aplikasi dan menjadi sumber data autentikasi.
export const AuthProvider = ({ children }) => {
  // User diambil dari localStorage agar sesi tetap ada saat browser direfresh.
  const [user, setUser] = useState(() => {
    // Data user tersimpan sebagai JSON string dari proses login sebelumnya.
    const savedUser = localStorage.getItem('user')
    // Jika belum ada user tersimpan, nilai awal dibuat null.
    return savedUser ? JSON.parse(savedUser) : null
  })
  // Token awal dibaca dari localStorage karena backend memakai JWT Bearer.
  const [token, setToken] = useState(() => localStorage.getItem('token'))
  // Loading mencegah halaman posts dibuka sebelum token lama selesai divalidasi ke backend.
  const [loading, setLoading] = useState(true)

  // Effect ini memvalidasi token lama agar token rusak/expired tidak membuat user terjebak di /posts.
  useEffect(() => {
    // Function async kecil dibuat di dalam effect untuk memanggil endpoint /auth/me.
    const validateSavedToken = async () => {
      // Jika tidak ada token, pengecekan selesai dan user tetap diarahkan ke login.
      if (!token) {
        setLoading(false)
        return
      }

      try {
        // Endpoint me memastikan token di localStorage masih diterima oleh backend CI3.
        const response = await authAPI.me()
        const payload = response.data?.data

        // Data user dari backend dipakai untuk menyegarkan localStorage.
        if (payload) {
          const userData = {
            id: payload.id,
            name: payload.username,
            username: payload.username,
            email: payload.email,
            role: payload.role,
          }

          localStorage.setItem('user', JSON.stringify(userData))
          setUser(userData)
        }
      } catch (error) {
        // Token lama yang gagal diverifikasi dihapus supaya user bisa login ulang dengan token baru.
        console.error('Token validation error:', error)
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        setToken(null)
        setUser(null)
      } finally {
        // Setelah validasi selesai, ProtectedRoute boleh menentukan halaman berikutnya.
        setLoading(false)
      }
    }

    validateSavedToken()
  }, [token])

  // Function login dipanggil dari halaman Login.jsx.
  const login = async (email, password) => {
    try {
      // Token lama dibersihkan dulu agar request berikutnya tidak memakai sesi yang rusak.
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // Backend CI3 mengembalikan struktur { status, message, data: { access_token, ...user } }.
      const response = await authAPI.login(email, password)
      const payload = response.data?.data

      // Jika token tidak ada, response dianggap tidak valid untuk login.
      if (!payload?.access_token) {
        return { success: false, message: response.data?.message || 'Token tidak ditemukan dari server.' }
      }

      // User disusun dari data backend tanpa menyimpan token di dalam object user.
      const userData = {
        id: payload.id,
        name: payload.username,
        username: payload.username,
        email: payload.email,
        role: payload.role,
      }

      // Token dan user disimpan agar sesi tetap aktif setelah refresh.
      localStorage.setItem('token', payload.access_token)
      localStorage.setItem('user', JSON.stringify(userData))
      setToken(payload.access_token)
      setUser(userData)

      // Return sukses dipakai oleh Login.jsx untuk redirect ke /posts.
      return { success: true }
    } catch (error) {
      // Pesan error diambil dari backend bila ada, lalu diberikan ke halaman login.
      const message = error.response?.data?.message || 'Login gagal. Periksa email dan password.'
      return { success: false, message }
    }
  }

  // Function logout menghapus sesi di client dan mencoba memanggil endpoint logout backend.
  const logout = async () => {
    try {
      // Endpoint logout CI3 tidak menyimpan blacklist token, tetapi tetap dipanggil sesuai modul.
      await authAPI.logout()
    } catch (error) {
      // Error logout tidak menghentikan pembersihan sesi lokal.
      console.error('Logout error:', error)
    } finally {
      // Data lokal dihapus agar ProtectedRoute mengembalikan user ke login.
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      setToken(null)
      setUser(null)
    }
  }

  // Object value ini yang dibaca melalui hook useAuth().
  const value = {
    user,
    token,
    loading,
    isAuthenticated: !!token,
    login,
    logout,
  }

  // Provider mengirim value ke seluruh child di dalam App.jsx.
  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

// Hook kecil agar komponen bisa membaca AuthContext dengan syntax yang ringkas.
export const useAuth = () => {
  // useContext mengambil value terbaru dari AuthProvider.
  const context = useContext(AuthContext)

  // Guard ini membantu menemukan error jika hook dipakai di luar AuthProvider.
  if (!context) {
    throw new Error('useAuth harus digunakan di dalam AuthProvider')
  }

  // Context dikembalikan ke komponen pemanggil.
  return context
}
