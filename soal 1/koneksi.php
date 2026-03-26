<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "tugas_besar";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika berhasil, tidak akan muncul apa-apa (clean)
// Tapi kalau mau tes, bisa aktifkan baris di bawah:
// echo "Koneksi Berhasil!"; 
?>