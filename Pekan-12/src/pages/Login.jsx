// useState mengatur nilai input email/password dan status loading.
import { useState } from 'react'
// useNavigate dipakai untuk redirect setelah login sukses.
import { useNavigate } from 'react-router-dom'
// useAuth menyediakan function login dari AuthContext.
import { useAuth } from '../context/AuthContext'

// Halaman Login meminta email dan password user CI3.
const Login = () => {
  // State email terhubung ke input email.
  const [email, setEmail] = useState('')
  // State password terhubung ke input password.
  const [password, setPassword] = useState('')
  // State error menampilkan pesan gagal login dari backend.
  const [error, setError] = useState('')
  // State loading mencegah submit ganda saat request sedang berjalan.
  const [loading, setLoading] = useState(false)
  // login berasal dari AuthContext dan akan menyimpan token.
  const { login } = useAuth()
  // navigate mengarahkan user ke halaman posts setelah berhasil login.
  const navigate = useNavigate()

  // handleSubmit menjalankan request login ke API CodeIgniter.
  const handleSubmit = async (event) => {
    event.preventDefault()
    setError('')
    setLoading(true)

    try {
      // Function login mengembalikan object success agar halaman bisa menentukan aksi berikutnya.
      const result = await login(email, password)

      if (result.success) {
        navigate('/posts')
      } else {
        setError(result.message)
      }
    } catch (err) {
      // Fallback jika terjadi error JavaScript di luar response axios.
      console.error('Login error:', err)
      setError(err.message || 'Terjadi kesalahan saat login. Pastikan server CodeIgniter sudah berjalan.')
    } finally {
      // Loading selalu dimatikan setelah request selesai.
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col">
      <div className="flex-1 flex flex-col items-center justify-center px-6 py-8">
        {/* Brand sederhana seperti contoh modul. */}
        <a href="#top" className="flex items-center mb-8 text-2xl font-semibold text-gray-900">
          <svg className="w-10 h-10 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
          </svg>
          Blog Posts
        </a>

        {/* Card form login. */}
        <div className="w-full max-w-md bg-white rounded-xl shadow-lg border border-gray-200 p-8">
          <h1 className="text-2xl font-bold text-gray-900 mb-6">Sign in to your account</h1>

          {/* Alert hanya muncul saat ada error. */}
          {error && (
            <div className="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
              {error}
            </div>
          )}

          <form className="space-y-6" onSubmit={handleSubmit}>
            {/* Input email wajib diisi karena backend login mencari user berdasarkan email. */}
            <div>
              <label htmlFor="email" className="block mb-2 text-sm font-medium text-gray-900">
                Your email
              </label>
              <input
                type="email"
                name="email"
                id="email"
                autoComplete="email"
                value={email}
                onChange={(event) => setEmail(event.target.value)}
                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                placeholder="name@company.com"
                required
              />
            </div>

            {/* Input password akan dikirim ke endpoint api/auth/login. */}
            <div>
              <label htmlFor="password" className="block mb-2 text-sm font-medium text-gray-900">
                Password
              </label>
              <input
                type="password"
                name="password"
                id="password"
                autoComplete="current-password"
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                placeholder="Masukkan password"
                className="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                required
              />
            </div>

            {/* Opsi remember me dibuat mengikuti tampilan modul. */}
            <div className="flex items-center justify-between">
              <div className="flex items-center">
                <input id="remember" type="checkbox" className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-2 focus:ring-blue-500" />
                <label htmlFor="remember" className="ml-2 text-sm text-gray-600">
                  Remember me
                </label>
              </div>
              <a href="#forgot-password" className="text-sm font-medium text-blue-600 hover:text-blue-500">
                Forgot password?
              </a>
            </div>

            {/* Tombol submit berubah teks saat proses login sedang berlangsung. */}
            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {loading ? 'Signing in...' : 'Sign in'}
            </button>
          </form>
        </div>
      </div>

      {/* Footer kecil khusus halaman login. */}
      <footer className="bg-white border-t border-gray-200 py-4">
        <p className="text-center text-sm text-gray-500">© 2026 Blog Posts. All rights reserved.</p>
      </footer>
    </div>
  )
}

// Export default agar route /login dapat merender halaman ini.
export default Login
