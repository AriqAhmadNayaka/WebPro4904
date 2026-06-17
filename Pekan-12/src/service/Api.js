import axios from "axios";

// Base URL mengarah ke backend CodeIgniter 3 yang ada di folder Pekan-09.
const API_URL = "/pemrograman_web/WebPro4904/WebPro4904/Pekan-09/ci3_project/api";

/* =========================
   Axios Instance
========================= */

const api = axios.create({
    baseURL: API_URL,

    // Semua request dikirim sebagai JSON karena API CI3 menerima data JSON.
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

/* =========================
   Request Interceptor
========================= */

api.interceptors.request.use(
    (config) => {

        // Token dari login disimpan di localStorage, lalu dikirim lewat header Authorization.
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

/* =========================
   Response Interceptor
========================= */

api.interceptors.response.use(

    (response) => response,

    (error) => {

        if (error.response && error.response.status === 401) {

            const requestUrl = error.config?.url || "";

            // Kalau token sudah tidak valid, user dibersihkan dan diarahkan login lagi.
            if (
                !requestUrl.includes("/login") &&
                !requestUrl.includes("/register")
            ) {
                localStorage.removeItem("token");
                localStorage.removeItem("user");

                window.location.href = "/login";
            }
        }

        return Promise.reject(error);
    }
);

/* =========================
   Authentication API
========================= */

export const authAPI = {

    // Endpoint autentikasi dipisah supaya pemanggilannya rapi dari halaman/context.
    login: (email, password) =>
        api.post("/auth/login", {
            email,
            password,
        }),

    register: (
        name,
        email,
        password,
        password_confirmation
    ) =>
        api.post("/auth/register", {
            name,
            email,
            password,
            password_confirmation,
        }),

    logout: () =>
        api.post("/auth/logout"),

    me: () =>
        api.post("/auth/me"),
};

/* =========================
   Posts API
========================= */

export const postsAPI = {

    // Endpoint post dipakai oleh dashboard, all post, dan halaman detail.
    getAll: (page = 1, perPage = 10) =>
        api.get(
            `/post?page=${page}&per_page=${perPage}`
        ),

    getById: (id) =>
        api.get(`/post/${id}`),
};

export default api;
