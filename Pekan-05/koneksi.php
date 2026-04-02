<?php
// Konfigurasi database
$host = "localhost"; // host tempat lokasi database dapat diganti dengan alamat server yang akan digunakan
$username = "root";  // username dari database
$password = "";      // password dari database
$database = "lifetrack"; // Nama database/schema yang ingin digunakan
// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);
// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>