// Axios dipakai sebagai HTTP client untuk mengakses REST API CodeIgniter.
import axios from 'axios'

// Base URL diarahkan langsung ke project CI3 Pekan-09 yang berada di dalam htdocs XAMPP.
const API_URL = 'http://localhost/Pemrograman%20Web/WebPro4904/Pekan-09/ci3_project/index.php/api'

// Instance axios pusat agar konfigurasi header dan interceptor tidak diulang di setiap request.
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
})

// Interceptor request menempelkan token JWT ke header Authorization bila user sudah login.
api.interceptors.request.use(
  (config) => {
    // Token disimpan di localStorage oleh AuthContext setelah proses login sukses.
    const token = localStorage.getItem('token')

    // Header Bearer dibaca oleh library Jwt.php pada backend CodeIgniter.
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    // Config wajib dikembalikan agar axios dapat melanjutkan request.
    return config
  },
  (error) => Promise.reject(error),
)

// Interceptor response menangani token yang sudah invalid atau expired.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Jika backend mengembalikan 401 di luar halaman auth, sesi lokal dihapus.
    if (error.response?.status === 401) {
      const requestUrl = error.config?.url || ''

      // Login/register tidak dipaksa redirect agar pesan error tetap bisa tampil di form.
      if (!requestUrl.includes('/login') && !requestUrl.includes('/register')) {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        // Redirect ke halaman login React saat token sudah tidak diterima backend.
        window.location.href = '/login'
      }
    }

    // Error tetap diteruskan supaya halaman dapat menampilkan pesan yang sesuai.
    return Promise.reject(error)
  },
)

// API autentikasi mengikuti route yang sudah ada di Pekan-09: api/auth/login, register, logout, me.
export const authAPI = {
  login: (email, password) => api.post('/auth/login', { email, password }),
  register: (name, email, password) => api.post('/auth/register', { name, email, password }),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
}

// API post mengikuti route Pekan-09: api/post untuk list dan api/post/:id untuk detail.
export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
}

// Export default dipakai bila suatu saat butuh request custom di luar authAPI/postsAPI.
export default api
