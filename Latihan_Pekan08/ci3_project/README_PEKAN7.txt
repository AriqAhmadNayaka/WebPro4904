# Pekan 7 - CodeIgniter 3 MVC

Aplikasi ini memakai database lama `db_webprodata` dan tabel `laporan` seperti pada pekan 6.

## Login
- URL: `http://localhost/ci3_project/`
- Username: `admin`
- Password: `12345`

## Struktur MVC/HMVC
- `application/modules/auth/controllers/Auth.php` untuk proses login/logout.
- `application/modules/auth/views/login.php` untuk tampilan login.
- `application/modules/dashboard/controllers/Dashboard.php` untuk dashboard dan proses CRUD + upload file.
- `application/modules/dashboard/models/Laporan_model.php` untuk query tabel `laporan`.
- `application/modules/dashboard/views/index.php` untuk tampilan dashboard.

## Database
Import file `db_webprodata.sql` bila database belum ada.

## Catatan tugas
- Dashboard hanya bisa diakses setelah login.
- Tombol `Simpan` menangani create/update dan upload file dalam satu submit.
- Delete menghapus data sekaligus file upload lama bila ada.
