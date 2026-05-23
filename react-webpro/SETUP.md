# React Blog Post - Modul 12

## Setup Selesai ✓

Proyek React + Vite untuk Blog Post sudah siap. Struktur lengkap dengan semua dependency dan konfigurasi.

### File/Folder yang Dibuat:

**Konfigurasi:**
- `package.json` - Dependencies React, Vite, Tailwind, axios, react-router-dom
- `vite.config.js` - Proxy ke `/Pekan-09` API backend
- `tailwind.config.js` - Tailwind CSS v3.4.19
- `postcss.config.js` - PostCSS config
- `index.html` - Entry point HTML

**Frontend Components:**
- `src/components/DefaultAvatar.jsx` - Avatar component dengan inisial nama
- `src/components/Navbar.jsx` - Navigation bar dengan logout button
- `src/components/Footer.jsx` - Footer
- `src/components/PostCard.jsx` - Kartu post untuk list view
- `src/components/ProtectedRoute.jsx` - Protected route component

**Pages:**
- `src/pages/Login.jsx` - Login form
- `src/pages/PostList.jsx` - Daftar post dengan pagination
- `src/pages/PostDetail.jsx` - Detail halaman per post

**Services & Context:**
- `src/services/api.js` - Axios client dengan interceptor, baseURL = `/Pekan-09/api`
- `src/context/AuthContext.jsx` - Auth context dengan login/logout dan localStorage

**Core:**
- `src/App.jsx` - Main app component dengan routing
- `src/main.jsx` - React DOM render
- `src/index.css` - Tailwind CSS config

### API Endpoints yang Digunakan:

- `POST /Pekan-09/api/auth/login` - Login
- `POST /Pekan-09/api/auth/logout` - Logout
- `GET /Pekan-09/api/auth/me` - Get user profile
- `GET /Pekan-09/api/post?page=1&per_page=10` - List posts
- `GET /Pekan-09/api/post/:id` - Get post detail

### Status Aplikasi:

✓ React + Vite berjalan di `http://localhost:5173`
✓ Login UI sudah tampil sempurna
✓ Router bekerja (login → posts → detail post)
✓ Axios configured dengan proxy ke `/Pekan-09`
✓ Protected routes sudah setup
✓ Tailwind CSS sudah terintegrasi
✓ localStorage untuk token & user

### Instruksi Menjalankan:

1. **Jalankan XAMPP:**
   - Buka XAMPP Control Panel
   - START Apache
   - START MySQL
   - Database: `db_pekan09_ci3`

2. **Verifikasi Backend API:**
   - Buka di browser: `http://localhost/Pemrograman_Web/WebPro4904/Pekan-09/`
   - Atau test endpoint di Postman:
     - `http://localhost/Pemrograman_Web/WebPro4904/Pekan-09/api/auth/login` (POST)
     - `http://localhost/Pemrograman_Web/WebPro4904/Pekan-09/api/post` (GET)

3. **Test Frontend:**
   - Aplikasi sudah berjalan di `http://localhost:5173/login`
   - Gunakan credentials dari database `db_pekan09_ci3`
   - Login → akan redirect ke `/posts`
   - Klik post untuk melihat detail

### Credentials untuk Test:

Login menggunakan user yang sudah ada di database `db_pekan09_ci3` (tabel `users`).

Jika belum ada, buat user baru melalui endpoint register Pekan-09.

### Notes:

- Token disimpan di localStorage dengan key `token`
- User data disimpan di localStorage dengan key `user`
- Aplikasi auto-redirect ke `/login` jika token expired (401 error)
- Proxy di vite.config.js mengubah request `/Pekan-09` → `http://localhost/Pekan-09`

Sudah siap untuk digunakan! 🚀
