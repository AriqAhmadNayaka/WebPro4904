import axios from "axios";

// Base URL API, sesuaikan dengan path project di server lokal
const API_URL = "/Pemograman_Web/WebPro4904/Pekan-09/api";

// Buat instance axios dengan konfigurasi default
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor request: otomatis sisipkan token ke setiap request
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

// Interceptor response: tangani error 401 (token expired/tidak valid)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || "";

      // Hindari redirect loop saat error terjadi di endpoint login/register
      if (!requestUrl.includes("/login") && !requestUrl.includes("/register")) {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/login"; // Paksa logout dan redirect ke login
      }
    }
    return Promise.reject(error);
  },
);

// Kumpulan endpoint autentikasi
export const authAPI = {
  login: (email, password) => api.post("/auth/login", { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", { name, email, password, password_confirmation }),
  logout: () => api.post("/auth/logout"),
  me: () => api.get("/auth/me"), // Ambil data user yang sedang login
};

// Kumpulan endpoint post
export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`), // Mendukung pagination
  getById: (id) => api.get(`/post/${id}`),
};

export default api;