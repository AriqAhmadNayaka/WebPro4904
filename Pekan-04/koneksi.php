<?php
// File koneksi utama ke MySQL (phpMyAdmin).
// Variabel $koneksi dipakai di halaman lain: register, auth, dan kelola peserta.
$koneksi = mysqli_connect("localhost", "root", "", "webpro") or die('database tidak terhubung');
?>
