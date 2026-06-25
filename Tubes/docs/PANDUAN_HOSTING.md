# Panduan Hosting WeBandoo+

Project ini bisa di-upload ke hosting PHP + MySQL seperti cPanel shared hosting atau VPS.

## Kebutuhan Server

- PHP 8.0 atau lebih baru
- MySQL/MariaDB
- Ekstensi PHP `mysqli`
- Apache dengan `.htaccess` aktif

## Langkah Deploy cPanel

1. Buat database MySQL dari cPanel.
2. Buat user database dan hubungkan user tersebut ke database dengan semua privilege.
3. Import `database/schema_hosting.sql` melalui phpMyAdmin.
4. Upload seluruh isi folder `Tubes` ke `public_html` atau subfolder domain.
5. Duplikat file `config/database.hosting.example.php` menjadi `config/database.local.php`.
6. Isi `config/database.local.php` dengan kredensial database hosting.
7. Buka domain di browser.
8. Login admin demo:

```text
Email: admin@webandoo.test
Password: admin123
```

## Catatan Keamanan

- Jangan upload password cPanel ke GitHub.
- File `config/database.local.php` berisi password database dan tidak perlu dibagikan.
- Setelah deploy, ganti password admin default melalui database atau fitur profil.
