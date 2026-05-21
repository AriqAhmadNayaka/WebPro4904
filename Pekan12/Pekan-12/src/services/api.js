import axios from "axios";

// Base URL diarahkan ke REST API CodeIgniter 3 dari praktikum modul 9.
const API_URL = "/Pemrograman%20Web/WebPro4904/Pekan-09/ci3_project/api";

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Setiap request otomatis membawa token agar endpoint yang dilindungi bisa diakses.
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => Promise.reject(error),
);

// Jika token sudah tidak valid, data login dihapus dan user diarahkan ke login.
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

// Kumpulan endpoint untuk proses autentikasi user.
export const authAPI = {
  login: (email, password) => api.post("/auth/login", { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", {
      name,
      email,
      password,
      password_confirmation,
    }),
  logout: () => api.post("/auth/logout"),
  me: () => api.get("/auth/me"),
};

// Kumpulan endpoint untuk mengambil data blog posts.
export const postsAPI = {
  getAll: (page = 1, perPage = 10) =>
    api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
};

export default api;
