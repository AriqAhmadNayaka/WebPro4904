import axios from 'axios';

const API_URL = '/ci3_project/api';
const API_URL_FALLBACK = 'http://localhost/ci3_project/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

const apiFallback = axios.create({
  baseURL: API_URL_FALLBACK,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
      config.headers['X-Access-Token'] = token;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

apiFallback.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
      config.headers['X-Access-Token'] = token;
    }
    return config;
  },
  (error) => Promise.reject(error),
);

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      const requestUrl = error.config?.url || '';

      if (!requestUrl.includes('/login') && !requestUrl.includes('/register')) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  },
);

export const authAPI = {
  login: async (email, password) => {
    try {
      return await api.post('/auth/login', { email, password });
    } catch (error) {
      return apiFallback.post('/auth/login', { email, password });
    }
  },
  register: (name, email, password, password_confirmation) =>
    api.post('/auth/register', { name, email, password, password_confirmation }),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

export const postsAPI = {
  getAll: async (page = 1, perPage = 10) => {
    try {
      return await api.get(`/post?page=${page}&per_page=${perPage}`);
    } catch (error) {
      return apiFallback.get(`/post?page=${page}&per_page=${perPage}`);
    }
  },
  getById: async (id) => {
    try {
      return await api.get(`/post/${id}`);
    } catch (error) {
      return apiFallback.get(`/post/${id}`);
    }
  },
};

export default api;
