import { useContext } from 'react'
import { AuthContext } from './auth'

export function useAuth() {
  const context = useContext(AuthContext)

  // Guard ini membantu menangkap kesalahan jika hook dipakai di luar AuthProvider.
  if (!context) {
    throw new Error('useAuth harus digunakan di dalam AuthProvider')
  }

  return context
}
