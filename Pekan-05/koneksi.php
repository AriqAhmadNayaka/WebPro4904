<?php
// File ini dipakai untuk membuat koneksi utama ke database MySQL.
// Host database yang digunakan adalah localhost.
// Username database yang dipakai adalah root.
// Password database dikosongkan sesuai pengaturan XAMPP default.
// Nama database yang dipakai oleh aplikasi adalah webpro.
// Variabel $koneksi akan digunakan di file lain seperti register, auth, dan kelola peserta.
$koneksi = mysqli_connect("localhost", "root", "", "webpro") or die("database tidak terhubung");
?>
