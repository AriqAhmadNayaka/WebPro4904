# PBO_10

Project ini dibuat agar soal Praktikum Pekan 10 bisa dijalankan di NetBeans sebagai proyek Java Maven.

## Penamaan file yang dipakai

- `PBO_10/pom.xml`
- `PBO_10/src/main/java/pbo_10/KoneksiDatabase.java`
- `PBO_10/src/main/java/pbo_10/Buku.java`
- `PBO_10/src/main/java/pbo_10/BukuService.java`
- `PBO_10/src/main/java/pbo_10/Main.java`

## Langkah konfigurasi di NetBeans

1. Buka `NetBeans`.
2. Pilih `File > Open Project`.
3. Arahkan ke folder `D:\xampp\htdocs\Pemrograman_Web\WebPro4904\PBO_10`.
4. Pastikan project terbaca sebagai `Maven Project`.
5. Pastikan JDK project memakai `JDK 21` atau versi yang tersedia di NetBeans.
6. Jalankan `XAMPP`, lalu aktifkan `Apache` dan `MySQL`.
7. Buka file [KoneksiDatabase.java](/D:/xampp/htdocs/Pemrograman_Web/WebPro4904/PBO_10/src/main/java/pbo_10/KoneksiDatabase.java:1).
8. Jika username/password MySQL berbeda, ubah bagian:
   - `USERNAME`
   - `PASSWORD`
9. NetBeans akan membaca dependency JDBC dari `pom.xml`:

```xml
<dependency>
    <groupId>com.mysql</groupId>
    <artifactId>mysql-connector-j</artifactId>
    <version>9.1.0</version>
</dependency>
```

10. Tunggu sampai Maven selesai mengunduh library.
11. Klik kanan file `Main.java`.
12. Pilih `Run File` atau jalankan project.

## Catatan sesuai soal

- Nama database: `perpustakaan`
- Nama tabel: `buku`
- Kolom:
  - `kode_buku` `VARCHAR` `PRIMARY KEY`
  - `judul_buku` `VARCHAR`
  - `pengarang` `VARCHAR`
  - `tahun_terbit` `INT`
  - `stok` `INT`
- Operasi yang dijalankan:
  - `CREATE DATABASE`
  - `CREATE TABLE`
  - `INSERT`
  - `SELECT`
  - `UPDATE`
  - `DELETE`
