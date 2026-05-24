import axios from "axios"; // Pastikan untuk menginstal axios dengan perintah: npm install axios

const API_URL = "/pemrograman_web/WebPro4904/Pekan-09/api"; // Sesuaikan dengan path API yang digunakan di server

// Membuat instance axios dengan konfigurasi dasar
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor untuk menambahkan token ke setiap request jika tersedia
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
  },
);

// Interceptor untuk menangani error 401 Unauthorized
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || "";

      if (!requestUrl.includes("/login") && !requestUrl.includes("/register")) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/login";
      }
    }
    return Promise.reject(error);
  },
);

// untuk mengelompokkan endpoint terkait otentikasi
export const authAPI = {
  login: (email, password) => api.post("/auth/login", { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", { name, email, password, password_confirmation }),
  logout: () => api.post("/auth/logout"),
  me: () => api.get("/auth/me"),
};

// untuk mengelompokkan endpoint terkait posts
export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
};

export default api;
