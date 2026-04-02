<?php
$host = "localhost";// Nama host database
$user = "root";// Nama pengguna database
$pass = "";// Kata sandi database
$db   = "webpro"; // Nama database

$conn = mysqli_connect($host, $user, $pass, $db);// Membuat koneksi ke database

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());// Jika koneksi gagal, tampilkan pesan error
}
?>