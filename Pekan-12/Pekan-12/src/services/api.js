// import axios digunakan untuk membuat instance API yang akan digunakan untuk berkomunikasi dengan backend.
import axios from "axios";

// API_URL adalah URL dasar untuk API backend yang akan digunakan dalam aplikasi. Dalam contoh ini, URL mengarah ke server lokal yang menjalankan API.
const API_URL = "http://localhost:8080/Pemrograman_Web/WebPro4904/Pekan-09/ci3_project/api";

// Kita membuat instance API menggunakan axios.create, yang memungkinkan kita untuk mengatur konfigurasi dasar seperti baseURL dan headers yang akan digunakan untuk semua permintaan API.
const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor request digunakan untuk menambahkan token otentikasi ke header setiap permintaan API jika token tersebut tersedia di localStorage. 
// Ini memastikan bahwa pengguna yang sudah login dapat mengakses endpoint yang memerlukan otentikasi tanpa harus menambahkan token secara manual setiap kali melakukan permintaan.
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

// Interceptor response digunakan untuk menangani kasus ketika token otentikasi tidak valid atau sudah kedaluwarsa. 
// Jika server merespons dengan status 401 (Unauthorized), interceptor ini akan memeriksa URL permintaan. 
// Jika URL tidak termasuk endpoint login atau register, maka token akan dihapus dari localStorage, dan pengguna akan diarahkan ke halaman login. 
// Ini membantu menjaga keamanan aplikasi dengan memastikan bahwa pengguna yang tidak sah tidak dapat mengakses sumber daya yang dilindungi.
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

// authAPI adalah objek yang berisi metode untuk berinteraksi dengan endpoint otentikasi di API backend. 
// Metode login digunakan untuk mengirim permintaan POST ke endpoint /auth/login dengan email dan password pengguna. 
// Metode register digunakan untuk mengirim permintaan POST ke endpoint /auth/register dengan nama, email, password, dan konfirmasi password pengguna. 
// Metode logout digunakan untuk mengirim permintaan POST ke endpoint /auth/logout untuk keluar dari sesi pengguna. 
// Metode me digunakan untuk mengirim permintaan GET ke endpoint /auth/me untuk mendapatkan informasi tentang pengguna yang sedang login.
export const authAPI = {
  login: (email, password) => api.post("/auth/login", { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post("/auth/register", { name, email, password, password_confirmation }),
  logout: () => api.post("/auth/logout"),
  me: () => api.get("/auth/me"),
};

// postsAPI adalah objek yang berisi metode untuk berinteraksi dengan endpoint posts di API backend. 
// Metode getAll digunakan untuk mengirim permintaan GET ke endpoint /post dengan parameter page dan per_page untuk mendapatkan daftar posts dengan pagination. 
// Metode getById digunakan untuk mengirim permintaan GET ke endpoint /post/{id} untuk mendapatkan detail dari post tertentu berdasarkan ID-nya.
export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
};

// Dengan struktur ini, kita memiliki satu file api.js yang mengelola semua interaksi dengan backend API.
export default api;