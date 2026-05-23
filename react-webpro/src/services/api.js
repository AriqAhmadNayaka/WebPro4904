import axios from 'axios';

const API_URL = '/Pemrograman_Web/WebPro4904/Pekan-09/api';
const USER_STORAGE_KEY = 'blogpost_users';
const POST_OWNER_KEY = 'blogpost_post_owners';

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  }
});

const getRegisteredUsers = () => {
  const stored = localStorage.getItem(USER_STORAGE_KEY);
  if (!stored) {
    const defaultUsers = [
      {
        id: 1,
        name: 'Administrator Utama',
        email: 'admin@cybervault.com',
        password: 'admin123',
        role: 'admin'
      }
    ];
    localStorage.setItem(USER_STORAGE_KEY, JSON.stringify(defaultUsers));
    return defaultUsers;
  }
  return JSON.parse(stored);
};

const saveRegisteredUsers = (users) => {
  localStorage.setItem(USER_STORAGE_KEY, JSON.stringify(users));
};

const getPostOwnershipMap = () => {
  const stored = localStorage.getItem(POST_OWNER_KEY);
  return stored ? JSON.parse(stored) : {};
};

const savePostOwnershipMap = (map) => {
  localStorage.setItem(POST_OWNER_KEY, JSON.stringify(map));
};

const mergePostOwner = (post) => {
  if (!post) return post;
  const owners = getPostOwnershipMap();
  return {
    ...post,
    ownerEmail: owners[post.id] || null
  };
};

const mergePostOwners = (posts) => {
  return posts.map((post) => mergePostOwner(post));
};

export const authService = {
  getUsers: async () => {
    return Promise.resolve({ data: getRegisteredUsers() });
  },

  getUserById: async (id) => {
    const user = getRegisteredUsers().find((item) => item.id === Number(id));
    return Promise.resolve({ data: user || null });
  },

  createUser: async ({ name, email, password, role = 'user' }) => {
    const users = getRegisteredUsers();
    const exists = users.some((item) => item.email === email.toLowerCase());
    if (exists) {
      return Promise.reject({ response: { data: { message: 'Email sudah terdaftar' } } });
    }
    const newUser = {
      id: users.length + 1,
      name,
      email: email.toLowerCase(),
      password,
      role
    };
    users.push(newUser);
    saveRegisteredUsers(users);
    return Promise.resolve({ data: newUser });
  },

  updateUser: async (id, data) => {
    const users = getRegisteredUsers();
    const index = users.findIndex((item) => item.id === Number(id));
    if (index === -1) {
      return Promise.reject({ response: { data: { message: 'User tidak ditemukan' } } });
    }
    if (data.email && users.some((item) => item.email === data.email.toLowerCase() && item.id !== Number(id))) {
      return Promise.reject({ response: { data: { message: 'Email sudah digunakan oleh user lain' } } });
    }
    users[index] = {
      ...users[index],
      ...data,
      email: data.email ? data.email.toLowerCase() : users[index].email
    };
    saveRegisteredUsers(users);
    return Promise.resolve({ data: users[index] });
  },

  deleteUser: async (id) => {
    let users = getRegisteredUsers();
    users = users.filter((item) => item.id !== Number(id));
    saveRegisteredUsers(users);
    return Promise.resolve({ data: { success: true } });
  },

  register: async ({ name, email, password }) => {
    const users = getRegisteredUsers();
    const exists = users.some((user) => user.email === email.toLowerCase());
    if (exists) {
      return Promise.reject({ response: { data: { message: 'Email sudah terdaftar' } } });
    }

    const newUser = {
      id: users.length + 1,
      name,
      email: email.toLowerCase(),
      password,
      role: 'user'
    };

    users.push(newUser);
    saveRegisteredUsers(users);

    const token = btoa(`${newUser.email}:${Date.now()}`);
    return Promise.resolve({ data: { user: newUser, token } });
  },

  login: async (email, password) => {
    const users = getRegisteredUsers();
    const user = users.find((item) => item.email === email.toLowerCase() && item.password === password);

    if (!user) {
      return Promise.reject({ response: { data: { message: 'Email atau password tidak cocok' } } });
    }

    const token = btoa(`${user.email}:${Date.now()}`);
    return Promise.resolve({ data: { user, token } });
  },

  logout: async () => {
    return Promise.resolve({ data: { status: true } });
  },

  getProfile: async () => {
    const user = JSON.parse(localStorage.getItem('user') || 'null');
    if (!user) {
      return Promise.reject({ response: { status: 401 } });
    }
    return Promise.resolve({ data: { user } });
  }
};

export const postService = {
  getPosts: async () => {
    const response = await apiClient.get('/posts');
    const data = response.data.data || response.data;
    return { ...response, data: mergePostOwners(data) };
  },

  getPostById: async (id) => {
    const response = await apiClient.get(`/posts/${id}`);
    const data = response.data.data || response.data;
    return { ...response, data: mergePostOwner(data) };
  },

  createPost: async ({ title, content, file, ownerEmail }) => {
    const form = new FormData();
    form.append('title', title);
    form.append('content', content);
    if (file) {
      form.append('file', file);
    }
    const response = await apiClient.post('/posts', form, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    const post = response.data.data || response.data;
    if (post && ownerEmail) {
      const owners = getPostOwnershipMap();
      owners[post.id] = ownerEmail;
      savePostOwnershipMap(owners);
    }
    return { ...response, data: mergePostOwner(post) };
  },

  updatePost: async (id, { title, content, file, ownerEmail }) => {
    const form = new FormData();
    form.append('_method', 'PUT');
    form.append('title', title);
    form.append('content', content);
    if (file) {
      form.append('file', file);
    }
    const response = await apiClient.post(`/posts/${id}`, form, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    const post = response.data.data || response.data;
    if (post && ownerEmail) {
      const owners = getPostOwnershipMap();
      owners[post.id] = ownerEmail;
      savePostOwnershipMap(owners);
    }
    return { ...response, data: mergePostOwner(post) };
  },

  deletePost: async (id) => {
    const response = await apiClient.delete(`/posts/${id}`);
    const owners = getPostOwnershipMap();
    delete owners[id];
    savePostOwnershipMap(owners);
    return response;
  }
};

export default apiClient;
