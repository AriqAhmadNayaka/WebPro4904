import axios from 'axios';

const API_URL = 'http://localhost/ci3_project/api';

const api = axios.create({
  baseURL: API_URL,
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
  login: (email, password) => api.post('/auth/login', { email, password }),
  register: (name, email, password, password_confirmation) =>
    api.post('/auth/register', { name, email, password, password_confirmation }),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

const extractPostList = (payload) => {
  if (Array.isArray(payload)) return payload;
  if (Array.isArray(payload?.data)) return payload.data;
  if (Array.isArray(payload?.posts)) return payload.posts;
  return [];
};

const requestPostList = async (page = 1, perPage = 10) => {
  const candidates = [
    `/post?page=${page}&per_page=${perPage}`,
    `/posts?page=${page}&per_page=${perPage}`,
  ];

  let lastError;
  for (const url of candidates) {
    try {
      const response = await api.get(url);
      return {
        ...response,
        data: extractPostList(response.data),
      };
    } catch (error) {
      lastError = error;
    }
  }
  throw lastError;
};

const requestPostDetail = async (id) => {
  const candidates = [`/post/${id}`, `/posts/${id}`];

  let lastError;
  for (const url of candidates) {
    try {
      const response = await api.get(url);
      const payload = response.data;
      return {
        ...response,
        data: payload?.data ?? payload,
      };
    } catch (error) {
      lastError = error;
    }
  }
  throw lastError;
};

export const postsAPI = {
  getAll: (page = 1, perPage = 10) => requestPostList(page, perPage),
  getById: (id) => requestPostDetail(id),
};

export default api;
