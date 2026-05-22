import axios from "axios";

const API_URL =
  import.meta.env.VITE_API_URL ||
  "http://localhost/WebPro4904/Pekan-9/index.php/api";

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

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

api.interceptors.response.use(
  (response) => {
    if (
      response.data &&
      typeof response.data === "object" &&
      "data" in response.data
    ) {
      return { ...response, data: response.data.data };
    }
    return response;
  },
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
  }
);

export const authAPI = {
  login: async (email, password) => {
    if (email === "admin@blog.com" && password === "password123") {
      return {
        data: {
          access_token: "dummy-token",
          user: { name: "Admin", email },
        },
      };
    }

    throw { response: { data: { message: "Email atau password salah" } } };
  },
  register: (name, email, password, password_confirmation) =>
    api.post("/register", { name, email, password, password_confirmation }),
  logout: () => Promise.resolve(),
  me: () => api.get("/me"),
};

export const postsAPI = {
  test: () => api.get("/test"),
  getAll: () => api.get("/posts"),
  getById: (id) => api.get(`/posts/${id}`),
  create: (data) => api.post("/posts", data),
  update: (id, data) => api.put(`/posts/${id}`, data),
  delete: (id) => api.delete(`/posts/${id}`),
};

export default api;
