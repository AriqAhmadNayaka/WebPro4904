// Axios dipakai untuk membuat client HTTP yang terpusat untuk seluruh request API.
import axios from "axios";

// Base URL mengarah ke backend Laravel pada folder praktikum Pekan-09.
const API_URL = "/Pemrograman_Web/WebPro4904/Pekan-09/api";

// Instance axios menyimpan konfigurasi dasar agar setiap request memakai baseURL dan header yang sama.
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor request menambahkan token Bearer dari localStorage ke setiap request jika tersedia.
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  // Error request diteruskan agar tetap bisa ditangani oleh pemanggil API.
  (error) => {
    return Promise.reject(error);
  },
);

// Interceptor response memeriksa response global, terutama saat token tidak valid atau sudah kedaluwarsa.
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Jika server mengembalikan 401, hapus sesi client agar user diminta login ulang.
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || "";

      // Request login/register tidak dialihkan supaya pesan errornya tetap tampil di halaman form.
      if (!requestUrl.includes("/login") && !requestUrl.includes("/register")) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/login";
      }
    }
    return Promise.reject(error);
  },
);

// authAPI mengelompokkan endpoint yang berhubungan dengan autentikasi user.
export const authAPI = {
  login: (email, password) => api.post("/auth/login", { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", { name, email, password, password_confirmation }),
  logout: () => api.post("/auth/logout"),
  me: () => api.get("/auth/me"),
};

// postsAPI mengelompokkan endpoint untuk membaca daftar post dan detail post.
export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
};

// Export instance api untuk kebutuhan request lain di luar authAPI dan postsAPI.
export default api;
