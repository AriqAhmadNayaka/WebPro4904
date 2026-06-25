# WeBandoo+ - Tugas Besar PHP Native

Project ini adalah lanjutan dari desain antarmuka pengguna menjadi aplikasi full-stack berbasis PHP Native/Vanilla.

## Struktur Folder

```text
Tubes/
|-- index.php
|-- login.php
|-- register.php
|-- home.php
|-- peta.php
|-- profil.php
|-- kuliner.php
|-- kafe.php
|-- warisan.php
|-- event.php
|-- eduction.php
|-- pengaturan.php
|-- keluar.php
|-- app/
|   |-- Controllers/
|   |-- Core/
|   `-- Models/
|-- config/
|   `-- database.php
|-- api/
|   |-- auth.php
|   `-- reviews.php
|-- partials/
|   |-- auth_guard.php
|   |-- guest_guard.php
|   `-- admin_guard.php
|-- database/
|   `-- schema.sql
|-- docs/
|   `-- INDIKATOR_TEKNIS.md
`-- assets/
    |-- css/
    `-- js/
```

## Penjelasan Folder

- `index.php`: pintu masuk aplikasi. User diarahkan ke `login.php` atau `home.php`.
- `login.php` dan `register.php`: halaman autentikasi pengguna.
- `home.php`, `peta.php`, `profil.php`, dan halaman lain: halaman utama aplikasi.
- `app/Controllers`, `app/Models`, dan `app/Core`: struktur MVC sederhana untuk API.
- `config/database.php`: koneksi MySQL dan persiapan database.
- `api/auth.php`: backend register, login, cek session, update profil, dan logout.
- `api/reviews.php`: backend CRUD review dan rating tempat.
- `partials/auth_guard.php`: melindungi halaman yang wajib login.
- `partials/guest_guard.php`: mencegah user yang sudah login membuka login/register.
- `partials/admin_guard.php`: guard halaman khusus admin.
- `database/schema.sql`: struktur tabel database.
- `database/schema_hosting.sql`: struktur tabel untuk import di hosting/cPanel.
- `docs/INDIKATOR_TEKNIS.md`: bukti indikator program, ERD, dan rancangan sistem.
- `docs/PANDUAN_HOSTING.md`: langkah deploy ke hosting.
- `assets/css` dan `assets/js`: file tampilan dan JavaScript frontend.

## Fitur Full-Stack

- Register user tersimpan ke tabel `users`.
- Login memakai session PHP.
- Edit profil mengambil dan menyimpan data dari database.
- Review/rating tempat di halaman `peta.php` memakai CRUD database.
- Bagian `Review Saya` di `profil.php` mengambil review milik user login.
- Arsitektur API memakai pola MVC sederhana di folder `app/`.
- REST API review mendukung `GET`, `POST`, `PUT`, dan `DELETE`.
- Role pengguna `user` dan `admin` tersedia melalui kolom `users.role`.
- Dashboard memakai Vue 3 sebagai framework front-end ringan.
- Admin dapat menambah konten/kartu tambahan pada halaman tertentu atau semua halaman.
- Admin dapat menambah/menghapus lokasi peta dan perubahan tersimpan di database.
- Login mendukung fitur `Ingat saya` / Remember Me.

## Akun Demo Admin

```text
Email: admin@webandoo.test
Password: admin123
```

## Bukti Indikator Teknis

Lihat `docs/INDIKATOR_TEKNIS.md` untuk ringkasan bukti MVC, REST API, role, asynchronous model, Vue, ERD, dan rancangan sistem.

## Panduan Hosting

Lihat `docs/PANDUAN_HOSTING.md`.

## Cara Menjalankan

1. Nyalakan Apache dan MySQL di XAMPP.
2. Buka:

```text
http://localhost/Pemrograman%20Web/WebPro4904/Tubes/
```

3. Register akun baru atau login dengan akun demo admin.
4. Tambahkan review di halaman peta.
5. Buka profil untuk melihat `Review Saya`.
