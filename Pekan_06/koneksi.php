<?php
// Konfigurasi database
$host = "localhost"; // host tempat lokasi database dapat diganti dengan alamat server yang akan digunakan
$username = "root"; // username dari database
$password = "";     // password dari database
$database = "db_webprodata"; // Nama database/schema yang ingin digunakan

// Membuat koneksi
$conn = new mysqli(hostname: $host, username: $username, password: $password, database: $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    echo "Koneksi ke database berhasil!";
}

// Tutup koneksi
$conn->close();
?>