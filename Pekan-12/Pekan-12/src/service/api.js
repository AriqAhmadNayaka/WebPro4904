import axios from 'axios';

const api = axios.create({
  baseURL: '/ci3_project/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');

    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
  },
  (error) => Promise.reject(error),
);

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const requestUrl = error.config?.url || '';

    if (
      error.response?.status === 401 &&
      !requestUrl.includes('/login') &&
      !requestUrl.includes('/register')
    ) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }

    return Promise.reject(error);
  },
);

export const authAPI = {
  login: (email, password) => api.post('/auth/login', { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post('/auth/register', { name, email, password, password_confirmation }),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

export const postsAPI = {
  getAll: (page = 1, perPage = 10) => api.get(`/post?page=${page}&per_page=${perPage}`),
  getById: (id) => api.get(`/post/${id}`),
};

export default api;
