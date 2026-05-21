import axios from "axios";

// Mengambil URL dasar API dari environment variable Vite, atau default ke "/api" jika tidak dikonfigurasi.
const API_URL = import.meta.env.VITE_API_BASE_URL || "/api";

// Membuat instance Axios kustom dengan konfigurasi default.
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Request Interceptor: Berfungsi untuk memodifikasi request sebelum dikirim ke server.
api.interceptors.request.use(
  (config) => {
    // Mengambil token autentikasi dari localStorage.
    const token = localStorage.getItem("token");
    // Jika token ada, tambahkan header Authorization dengan format Bearer Token.
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    // Menangani error sebelum request dikirim.
    return Promise.reject(error);
  },
);

// Response Interceptor: Berfungsi untuk memproses response atau menangani error secara global setelah diterima dari server.
api.interceptors.response.use(
  (response) => response, // Jika respons sukses (status 2xx), langsung kembalikan respons tersebut.
  (error) => {
    // Menangani kesalahan respons (misalnya status HTTP 4xx atau 5xx).
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || "";

      // Jika error 401 (Unauthorized) terjadi bukan di endpoint login atau register, 
      // hapus data sesi lokal dan arahkan pengguna kembali ke halaman login.
      if (!requestUrl.includes("/login") && !requestUrl.includes("/register")) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/login";
      }
    }
    return Promise.reject(error);
  },
);

// Kumpulan fungsi pembungkus API untuk proses autentikasi.
export const authAPI = {
  // Fungsi untuk mengirimkan request login.
  login: (email, password) => api.post("/auth/login", { email, password }),
  
  // Fungsi untuk melakukan pendaftaran pengguna baru.
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", {
      name,
      email,
      password,
      password_confirmation,
    }),
  
  // Fungsi untuk logout.
  logout: () => api.post("/auth/logout"),
  
  // Fungsi untuk mengambil data profil pengguna saat ini.
  me: () => api.get("/auth/me"),
};

// Kumpulan fungsi pembungkus API untuk pengelolaan artikel/post.
export const postsAPI = {
  // Mengambil daftar semua artikel dengan opsi pagination.
  getAll: (page = 1, perPage = 10) =>
    api.get(`/post?page=${page}&per_page=${perPage}`),
  
  // Mengambil detail artikel berdasarkan ID.
  getById: (id) => api.get(`/post/${id}`),
};

export default api;
