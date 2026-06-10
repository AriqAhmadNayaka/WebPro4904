import axios from 'axios';

const API_BASE_URL =
  import.meta.env.VITE_API_URL ||
  '/Pemrograman_Web/WebPro4904/smarttrash_monitor2/codeigniter/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

const unwrap = (response) => response.data?.data ?? response.data;

export const authService = {
  login: async ({ email, password }) => {
    const response = await apiClient.post('/auth/login', { email, password });
    return unwrap(response);
  },

  register: async ({ name, email, password, confirm_password }) => {
    const response = await apiClient.post('/auth/register', {
      name,
      email,
      password,
      confirm_password,
    });
    return unwrap(response);
  },

  me: async () => {
    const response = await apiClient.get('/auth/me');
    return unwrap(response);
  },

  logout: async () => {
    const response = await apiClient.post('/auth/logout');
    return unwrap(response);
  },

  requestReset: async ({ email }) => {
    const response = await apiClient.post('/auth/request-reset', { email });
    return unwrap(response);
  },

  resetPassword: async ({ email, otp, password, confirm_password }) => {
    const response = await apiClient.post('/auth/reset-password', {
      email,
      otp,
      password,
      confirm_password,
    });
    return unwrap(response);
  },
};

export const portalService = {
  getDashboard: async () => {
    const response = await apiClient.get('/portal/dashboard');
    return unwrap(response);
  },

  getTimeline: async () => {
    const response = await apiClient.get('/portal/timeline');
    return unwrap(response);
  },

  getNotifications: async () => {
    const response = await apiClient.get('/portal/notifications');
    return unwrap(response);
  },

  getAccount: async () => {
    const response = await apiClient.get('/portal/account');
    return unwrap(response);
  },

  updateAccount: async ({ name, email }) => {
    const response = await apiClient.post('/portal/account', { name, email });
    return unwrap(response);
  },
};

export default apiClient;
