import axios from "axios";

// Menentukan URL dasar untuk REST API CodeIgniter 3
const API_URL = "/ci3_project/api";

// Membuat instance Axios dengan konfigurasi dasar
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor Permintaan (Request Interceptor)
// Mengambil token JWT dari localStorage jika ada, lalu menyematkannya ke header Authorization
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor Respons (Response Interceptor)
// Memeriksa jika respons menghasilkan error 401 (Unauthorized/Token kedaluwarsa atau tidak valid).
// Jika iya, hapus token/user dari localStorage dan alihkan pengguna kembali ke halaman login.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || "";
      // Kecualikan endpoint login dan register agar tidak terjadi perulangan pengalihan
      if (!requestUrl.includes("/login") && !requestUrl.includes("/register")) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/login";
      }
    }
    return Promise.reject(error);
  }
);

// Kumpulan endpoint API untuk Autentikasi Pengguna
export const authAPI = {
  // Melakukan login dengan email & password
  login: (email, password) => api.post("/auth/login", { email, password }),
  // Melakukan pendaftaran user baru
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", { name, email, password, password_confirmation }),
  // Melakukan logout dari sistem
  logout: () => api.post("/auth/logout"),
  // Mengambil informasi profil user saat ini
  me: () => api.get("/auth/me"),
};

// Kumpulan endpoint API untuk pengelolaan artikel/Postingan
export const postsAPI = {
  // Mengambil semua artikel dengan pagination default
  getAll: (page = 1, perPage = 10) =>
    api.get(`/post?page=${page}&per_page=${perPage}`),
  // Mengambil artikel spesifik berdasarkan ID
  getById: (id) => api.get(`/post/${id}`),
};

export default api;
