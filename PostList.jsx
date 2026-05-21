import { useMemo, useState } from 'react'
import { authAPI } from '../services/api'
import { AuthContext } from './auth'

const STORAGE_KEY = 'react_posts_auth'

// Membaca data login dari localStorage agar user tidak logout saat halaman di-refresh.
function readStoredAuth() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    return raw ? JSON.parse(raw) : null
  } catch {
    return null
  }
}

export function AuthProvider({ children }) {
  const [auth, setAuth] = useState(() => readStoredAuth())

  // Mengirim email dan password ke API, lalu menyimpan token yang dikembalikan.
  const login = async (email, password) => {
    const response = await authAPI.login(email, password)
    const payload = response.data
    const nextAuth = {
      token: payload.access_token,
      tokenType: payload.token_type || 'Bearer',
      user: payload.data,
    }

    localStorage.setItem(STORAGE_KEY, JSON.stringify(nextAuth))
    setAuth(nextAuth)
    return nextAuth
  }

  // Menghapus data login dari browser.
  const logout = () => {
    localStorage.removeItem(STORAGE_KEY)
    setAuth(null)
  }

  // Value ini yang nanti dipakai oleh useAuth di komponen lain.
  const value = useMemo(
    () => ({
      user: auth?.user || null,
      token: auth?.token || null,
      isAuthenticated: Boolean(auth?.token),
      login,
      logout,
    }),
    [auth],
  )

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}
