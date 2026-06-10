import axios from 'axios';

const API_URL =
  import.meta.env.VITE_API_URL ||
  'http://localhost/Pemrograman_Web/WebPro4904/smarttrash_monitor2/codeigniter/api/monitoring';

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
});

export const monitoringService = {
  getAll: async () => {
    const response = await api.get('');
    return response.data.data || [];
  },

  create: async (payload) => {
    const response = await api.post('', payload);
    return response.data;
  },

  update: async (id, payload) => {
    const response = await api.put(`/${id}`, payload);
    return response.data;
  },

  remove: async (id) => {
    const response = await api.delete(`/${id}`);
    return response.data;
  }
};
