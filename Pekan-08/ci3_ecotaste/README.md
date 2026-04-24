# EcoTaste CI3 Ready

Folder ini berisi aplikasi yang sudah disusun mengikuti pola CodeIgniter 3:

- `application/controllers/Auth.php`: login dan logout
- `application/controllers/Dashboard.php`: halaman utama setelah login
- `application/controllers/Menu.php`: CRUD dan upload file dengan satu tombol `Simpan`
- `application/models`: akses database
- `application/views`: tampilan login, dashboard, dan form menu
- `sql/ecotaste_ci3.sql`: database dan data awal

Cara pakai:

1. Salin isi folder ini ke project CodeIgniter 3 yang sudah memiliki folder inti `system`.
2. Import file `sql/ecotaste_ci3.sql`.
3. Atur `application/config/config.php` bagian `base_url`.
4. Pastikan folder `uploads/menu` bisa ditulis oleh server.

Login awal:

- Username: `admin`
- Password: `admin123`
